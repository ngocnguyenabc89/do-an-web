<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    /**
     * Danh sách
     * method: get
     */
    public function viewList()
    {
        $orderList = DB::table('don_hang')->paginate(20);

        return view('admin.order.list', ['orderList' => $orderList]);
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

            // Lấy danh sách sản phẩm
            $productList = DB::table('san_pham')
                ->join('danh_muc', 'danh_muc.ma_danh_muc', '=', 'san_pham.ma_danh_muc')
                ->where('tinh_trang', 1)
                ->paginate(10);
        } catch (Exception $ex) {
            Session::put('fail', $ex->getMessage());
            return Redirect::back();
        }

        if ($order->tinh_trang == 0) {
            return view('admin.order.order-canceled', ['order' => $order, 'orderDetailList' => $orderDetailList, 'productList' => $productList]);
        } elseif ($order->tinh_trang == 1) {
            return view('admin.order.order-pending', ['order' => $order, 'orderDetailList' => $orderDetailList, 'productList' => $productList]);
        } elseif ($order->tinh_trang == 2) {
            return view('admin.order.order-confirmed', ['order' => $order, 'orderDetailList' => $orderDetailList, 'productList' => $productList]);
        } elseif ($order->tinh_trang == 3) {
            return view('admin.order.order-completed', ['order' => $order, 'orderDetailList' => $orderDetailList, 'productList' => $productList]);
        }
    }

    /**
     * Update Quantity
     * method: post
     */
    public function updateQuantity(Request $request)
    {
        // dd($request->all());

        // Lấy thông tin từ form
        $orderId = $request->order_id;
        $productIdList = $request->product_id_list;
        $quantityUpdatedList = $request->quantity_updated_list;

        try {

            // Update thông tin trong db. Sản phẩm nào có số lượng bán = 0 thì xóa khỏi bảng chi tiết đơn hàng
            $size = count($productIdList);
            for ($i = 0; $i < $size; $i++) {

                // Nếu số lượng > 0 thì cập nhật
                if ($quantityUpdatedList[$i] > 0) {

                    $product = DB::table('san_pham')->where('ma_san_pham', $productIdList[$i])->first();
                    $productPrice = $product->gia;
                    // dd($productPrice, $productIdList[$i], $quantityUpdatedList[$i], $orderId);

                    DB::table('chi_tiet_don_hang')
                        ->where([['ma_don_hang', $orderId], ['ma_san_pham', $productIdList[$i]]])
                        ->updateOrInsert(
                            ['ma_don_hang' => $orderId, 'ma_san_pham' => $productIdList[$i], 'don_gia' => $productPrice],
                            ['so_luong_ban' => $quantityUpdatedList[$i], 'thanh_tien' => $productPrice * $quantityUpdatedList[$i]]
                        );
                } else {
                    DB::table('chi_tiet_don_hang')->where([['ma_don_hang', $orderId], ['ma_san_pham', $productIdList[$i]]])->delete();
                }
            }

            // Cập nhật lại số tiền của đơn hàng và lịch sử
            $amountTotal = DB::table('chi_tiet_don_hang')
                ->where('ma_don_hang', $orderId)
                ->sum('thanh_tien');

            $order = DB::table('don_hang')->where('ma_don_hang', $orderId)->first();
            $textHistory = $order->lich_su;
            $textHistory .= date("H:m:s d/m/y") . " - " . "user #" . Session::get('user_id') . " : Cập nhật giỏ hàng\r\n";

            DB::table('don_hang')->where('ma_don_hang', $orderId)->update([
                'tong_tien' => $amountTotal,
                'lich_su' => $textHistory,
                'nhan_vien_cap_nhat' => Session::get('user_id')
            ]);
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }
        Session::flash('success', 'Đã cập nhật thông tin');
        return Redirect::back();
    }


    /**
     * Update Customer
     * method: POST
     */
    public function updateCustomer(Request $request)
    {
        // dd($request->all());
        // dd(explode("T", $request->customer_time_delivery)[0]);
        $order_id = $request->order_id;
        $customer_time_delivery = explode("T", $request->customer_time_delivery)[0] . " " . explode("T", $request->customer_time_delivery)[1];
        $customer_name = $request->customer_name;
        $customer_phone = $request->customer_phone;
        $customer_address = $request->customer_address;
        $customer_note = $request->customer_note;

        try {
            $order = DB::table('don_hang')->where('ma_don_hang', $order_id)->first();
            $textHistory = $order->lich_su;
            $textHistory .= date("H:m:s d/m/y") . " - " . "user #" . Session::get('user_id') . " : Cập nhật thông tin khách hàng\r\n";

            DB::table('don_hang')
                ->where('ma_don_hang', $order_id)
                ->update([
                    'ten_khach_hang' => $customer_name,
                    'dien_thoai_khach_hang' => $customer_phone,
                    'dia_chi_giao_hang' => $customer_address,
                    'thoi_gian_giao_hang' => $customer_time_delivery,
                    'ghi_chu_khach_hang' => $customer_note,
                    'lich_su' => $textHistory,
                    'nhan_vien_cap_nhat' => Session::get('user_id')
                ]);
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }
        Session::flash('success', 'Đã cập nhật thông tin');
        return Redirect::back();
    }


    /**
     * Confirm Order
     * method: post
     * đổi trang thái đơn hàng = 2 ,3
     */
    public function confirmOrder(Request $request, $order_status)
    {
        // dd($request->all());

        try {
            // Lấy lịch sử của đơn hàng
            $order = DB::table('don_hang')->where('ma_don_hang', $request->order_id)->first();
            $textHistory = $order->lich_su;

            if ($order_status == 2) {
                $text_status = ' ĐÃ XÁC NHẬN ĐƠN HÀNG';
            } elseif ($order_status == 3) {
                $text_status = ' ĐÃ GIAO HÀNG';
            }

            $textHistory .= date("H:m:s d/m/y") . " - " . "user #" . Session::get('user_id') . " : " . $text_status . "\r\n";

            // Cập nhật trạng thái 2 và lịch sử
            DB::table('don_hang')
                ->where('ma_don_hang', $request->order_id)
                ->update(['tinh_trang' => $order_status, 'lich_su' => $textHistory, 'nhan_vien_cap_nhat' => Session::get('user_id'), 'ghi_chu_nhan_vien' => $request->user_note]);
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }

        return Redirect::to('admin/order/info/' . $request->order_id);
    }

    /**
     * Cancel Order
     * method: get
     * đổi trạng thái đơn hàng = 0
     */

    public function cancelOrder($order_id)
    {
        try {
            // Lấy lịch sử của đơn hàng
            $order = DB::table('don_hang')->where('ma_don_hang', $order_id)->first();
            $textHistory = $order->lich_su;

            $textHistory .= date("H:m:s d/m/y") . " - " . "user #" . Session::get('user_id') . " : ĐÃ HỦY\r\n";

            // Cập nhật trạng thái 2 và lịch sử
            DB::table('don_hang')
                ->where('ma_don_hang', $order_id)
                ->update(['tinh_trang' => 0, 'lich_su' => $textHistory, 'nhan_vien_cap_nhat' => Session::get('user_id')]);
        } catch (Exception $ex) {

            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }

        return Redirect::to('admin/order/info/' . $order_id);
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
