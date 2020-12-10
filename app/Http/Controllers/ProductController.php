<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    /**
     * Danh sách
     * method: get
     */
    public function viewList()
    {
        $productList = DB::table('san_pham')
            ->join('danh_muc', 'danh_muc.ma_danh_muc', '=', 'san_pham.ma_danh_muc')
            ->paginate(10);

        return view('admin.product.list', ['productList' => $productList]);
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
        return view('admin.product.create', ['categoryList' => $categoryList]);
    }

    /**
     * Create
     * method: post
     */
    public function create(Request $request)
    {
        // kiểm tra dữ liệu đầu vào. 
        $this->validate($request, [
            'product_name' => 'required',
            'product_price' => 'required',
            'product_quantity' => 'required',
            'product_status' => 'required',
            'product_type' => 'required',
            'product_category' => 'required',
            'product_image' => 'required',
            'product_description' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'product_name' => 'Tên',
            'product_price' => 'Giá bán',
            'product_quantity' => 'Số lượng',
            'product_status' => 'Tình trạng',
            'product_type' => 'Phân loại',
            'product_category' => 'Danh mục',
            'product_image' => 'Ảnh',
            'product_description' => 'Mô tả',
        ]);

        $data = [
            'product_name' => trim($request->product_name, " "), //cắt khoảng trắng 2 bên của tên
        ];

        try {

            // Kiểm tra tên đã tồn tại chưa
            $checkExists = DB::select('SELECT * FROM san_pham WHERE LOWER(ten_san_pham COLLATE utf8mb4_bin) = LOWER(:product_name) ;', ['product_name' => $data['product_name']]);

            if (count($checkExists) > 0) {
                return view('admin.product.create', ['result' => 'fail', 'message' => 'Tên đã tồn tại']);
            }

            // Lưu ảnh vô thư mục product: public/storage/product/<image_name>
            if ($request->hasFile('product_image')) {
                $imagePath = Storage::putFile('product', $request->file('product_image')); // trả về đường dẫn
                $imageName = basename(($imagePath)); // trả về tên file
            }
            // Lưu db
            DB::table('san_pham')
                ->insert([
                    'ten_san_pham' => $data['product_name'],
                    'mo_ta_san_pham' => $request->product_description,
                    'gia' => $request->product_price,
                    'so_luong' => $request->product_quantity,
                    'tinh_trang' => $request->product_status,
                    'phan_loai' => $request->product_type,
                    'anh_san_pham' => $imageName,
                    'ma_danh_muc' => $request->product_category
                ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.product.list', ['result' => 'success']);
    }

    /**
     * Info
     * method: get
     */
    public function viewInfo($product_id)
    {
        try {
            $categoryList = DB::table('danh_muc')->get();
            $product = DB::table('san_pham')->where('ma_san_pham', '=', $product_id)->first();

            // kiểm tra id tồn tại ?
            if ($product == null) {
                return view('admin.product.list', ['result' => 'fail', 'message' => 'Không tồn tại']);
            }
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.product.info', ['product' => $product, 'categoryList' => $categoryList]);
    }

    /**
     * Edit
     * method: post
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'product_name' => 'required',
            'product_price' => 'required',
            'product_quantity' => 'required',
            'product_status' => 'required',
            'product_type' => 'required',
            'product_category' => 'required',
            'product_description' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'product_id' => 'Mã',
            'product_name' => 'Tên',
            'product_price' => 'Giá bán',
            'product_quantity' => 'Số lượng',
            'product_status' => 'Tình trạng',
            'product_type' => 'Phân loại',
            'product_category' => 'Danh mục',
            'product_description' => 'Mô tả',
        ]);

        try {

            // Cập nhật image
            if ($request->has('product_image')) {
                // Lấy đường dẫn ảnh trong db
                $product = DB::table('san_pham')->where('ma_san_pham', '=', $request->product_id)->first();
                $oldImagePath = $product->anh_san_pham;

                // Xóa ảnh cũ
                File::delete('storage/product/' . $oldImagePath);

                // lưu ảnh mới vào thư mục
                $imagePath = Storage::putFile('product', $request->product_image);
                $imageName = basename($imagePath);

                // cập nhật ảnh trong db
                DB::table('san_pham')->where('ma_san_pham', '=', $request->product_id)->update(['anh_san_pham' => $imageName]);
            }

            // Cập nhật tên | giá | số lượng | tình trạng | phân loại | danh mục | mô tả
            DB::table('san_pham')
                ->where('ma_san_pham', '=', $request->product_id)
                ->update([
                    'ten_san_pham' => $request->product_name,
                    'mo_ta_san_pham' => $request->product_description,
                    'gia' => $request->product_price,
                    'so_luong' => $request->product_quantity,
                    'tinh_trang' => $request->product_status,
                    'phan_loai' => $request->product_type,
                    'ma_danh_muc' => $request->product_category
                ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.product.list', ['result' => 'success']);
    }

    /**
     * Delete
     * method: get
     */
    public function delete($product_id)
    {
        try {
            // Lấy đường dẫn ảnh trong db
            $product = DB::table('san_pham')->where('ma_san_pham', '=', $product_id)->first();
            $oldImagePath = $product->anh_san_pham;

            // Xóa trong db
            DB::table('san_pham')->where('ma_san_pham', '=', $product_id)->delete();

            // Xóa ảnh trong thư mục
            File::delete('storage/product/' . $oldImagePath);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.product.list', ['result' => 'success']);
    }
}
