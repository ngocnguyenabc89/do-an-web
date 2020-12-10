<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{

    /**
     * Danh sách
     * method: get
     */
    public function viewList()
    {
        $orderList = DB::table('don_hang')->paginate(10);

        return view('admin.order.list', ['orderList' => $orderList]);
    }

    /**
     * view Tạo
     * method: get
     */
    public function viewCreate()
    {
        try {
            $categoryList = DB::table('danh_muc')->get();
        } catch (Exception $ex) {
            dd($ex);
        }
        return view('admin.order.create', ['categoryList' => $categoryList]);
    }

    /**
     * Create
     * method: post
     */
    public function create(Request $request)
    {
        // kiểm tra dữ liệu đầu vào. 
        $this->validate($request, [
            'order_name' => 'required',
            'order_price' => 'required',
            'order_quantity' => 'required',
            'order_status' => 'required',
            'order_type' => 'required',
            'order_category' => 'required',
            'order_image' => 'required',
            'order_description' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'order_name' => 'Tên',
            'order_price' => 'Giá bán',
            'order_quantity' => 'Số lượng',
            'order_status' => 'Tình trạng',
            'order_type' => 'Phân loại',
            'order_category' => 'Danh mục',
            'order_image' => 'Ảnh',
            'order_description' => 'Mô tả',
        ]);

        $data = [
            'order_name' => trim($request->order_name, " "), //cắt khoảng trắng 2 bên của tên
        ];

        try {

            // Kiểm tra tên đã tồn tại chưa
            $checkExists = DB::select('SELECT * FROM don_hang WHERE LOWER(ten_don_hang COLLATE utf8mb4_bin) = LOWER(:order_name) ;', ['order_name' => $data['order_name']]);

            if (count($checkExists) > 0) {
                return view('admin.order.create', ['result' => 'fail', 'message' => 'Tên đã tồn tại']);
            }

            // Lưu ảnh vô thư mục order: public/storage/order/<image_name>
            if ($request->hasFile('order_image')) {
                $imagePath = Storage::putFile('order', $request->file('order_image')); // trả về đường dẫn
                $imageName = basename(($imagePath)); // trả về tên file
            }
            // Lưu db
            DB::table('don_hang')
                ->insert([
                    'ten_don_hang' => $data['order_name'],
                    'mo_ta_don_hang' => $request->order_description,
                    'gia' => $request->order_price,
                    'so_luong' => $request->order_quantity,
                    'tinh_trang' => $request->order_status,
                    'phan_loai' => $request->order_type,
                    'anh_don_hang' => $imageName,
                    'ma_danh_muc' => $request->order_category
                ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.order.list', ['result' => 'success']);
    }

    /**
     * Info
     * method: get
     */
    public function viewInfo($order_id)
    {
        try {

            // Lấy đơn hàng. kiểm tra tồn tại ?   
            $order = DB::table('don_hang')->where('ma_don_hang', '=', $order_id)->first();
            if ($order == null) {
                return view('admin.order.list', ['result' => 'fail', 'message' => 'Không tồn tại']);
            }

            // Lấy chi tiết đơn hàng
            $orderDetailList = DB::table('chi_tiet_don_hang')
                ->join('san_pham', 'san_pham.ma_san_pham', '=', 'chi_tiet_don_hang.ma_san_pham')
                ->where('ma_don_hang', '=', $order_id)
                ->get();
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.order.info', ['order' => $order, 'orderDetailList' => $orderDetailList]);
    }

    /**
     * Edit
     * method: post
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required',
            'order_name' => 'required',
            'order_price' => 'required',
            'order_quantity' => 'required',
            'order_status' => 'required',
            'order_type' => 'required',
            'order_category' => 'required',
            'order_description' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'order_id' => 'Mã',
            'order_name' => 'Tên',
            'order_price' => 'Giá bán',
            'order_quantity' => 'Số lượng',
            'order_status' => 'Tình trạng',
            'order_type' => 'Phân loại',
            'order_category' => 'Danh mục',
            'order_description' => 'Mô tả',
        ]);

        try {

            // Cập nhật image
            if ($request->has('order_image')) {
                // Lấy đường dẫn ảnh trong db
                $order = DB::table('don_hang')->where('ma_don_hang', '=', $request->order_id)->first();
                $oldImagePath = $order->anh_don_hang;

                // Xóa ảnh cũ
                File::delete('storage/order/' . $oldImagePath);

                // lưu ảnh mới vào thư mục
                $imagePath = Storage::putFile('order', $request->order_image);
                $imageName = basename($imagePath);

                // cập nhật ảnh trong db
                DB::table('don_hang')->where('ma_don_hang', '=', $request->order_id)->update(['anh_don_hang' => $imageName]);
            }

            // Cập nhật tên | giá | số lượng | tình trạng | phân loại | danh mục | mô tả
            DB::table('don_hang')
                ->where('ma_don_hang', '=', $request->order_id)
                ->update([
                    'ten_don_hang' => $request->order_name,
                    'mo_ta_don_hang' => $request->order_description,
                    'gia' => $request->order_price,
                    'so_luong' => $request->order_quantity,
                    'tinh_trang' => $request->order_status,
                    'phan_loai' => $request->order_type,
                    'ma_danh_muc' => $request->order_category
                ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.order.list', ['result' => 'success']);
    }

    /**
     * Delete
     * method: get
     */
    public function delete($order_id)
    {
        try {
            // Lấy đường dẫn ảnh trong db
            $order = DB::table('don_hang')->where('ma_don_hang', '=', $order_id)->first();
            $oldImagePath = $order->anh_don_hang;

            // Xóa trong db
            DB::table('don_hang')->where('ma_don_hang', '=', $order_id)->delete();

            // Xóa ảnh trong thư mục
            File::delete('storage/order/' . $oldImagePath);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.order.list', ['result' => 'success']);
    }
}
