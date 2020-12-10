<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{

    /**
     * Danh sách
     * method: get
     */
    public function viewList()
    {
        $categoryList = DB::table('danh_muc')->paginate(5);

        return view('admin.category.list', ['categoryList' => $categoryList]);
    }

    /**
     * view Tạo
     * method: get
     */
    public function viewCreate()
    {
        return view('admin.category.create');
    }

    /**
     * Create
     * method: post
     */
    public function create(Request $request)
    {
        // kiểm tra dữ liệu đầu vào
        $this->validate($request, [
            'category_name' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'category_name' => 'Tên',
        ]);

        $data = [
            'category_name' => trim($request->category_name, " "), //cắt khoảng trắng 2 bên của tên
            'category_description' => trim($request->category_description, " ")
        ];

        try {

            // Kiểm tra tên danh mục đã tồn tại chưa
            $checkExists = DB::select('SELECT * FROM danh_muc WHERE LOWER(ten_danh_muc COLLATE utf8mb4_bin) = LOWER(:category_name) ;', ['category_name' => $data['category_name']]);

            if (count($checkExists) > 0) {
                return view('admin.category.create', ['result' => 'fail', 'message' => 'Danh mục đã tồn tại']);
            }

            // Lưu db
            DB::table('danh_muc')->insert(['ten_danh_muc' => $data['category_name'], 'mo_ta' => $request->category_description]);
        } catch (Exception $ex) {
            return view('admin.category.create', ['result' => "fail", "message" => $ex->getMessage()]);
        }

        return view('admin.category.list', ['result' => 'success']);
    }

    /**
     * Info
     * method: get
     */
    public function viewInfo($category_id)
    {
        try {
            $category = DB::table('danh_muc')->where('ma_danh_muc', '=', $category_id)->first();

            // kiểm tra danh mục tồn tại ?
            if ($category == null) {
                return view('admin.category.list', ['result' => 'fail', 'message' => 'Không tồn tại']);
            }
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.category.info', ['category' => $category]);
    }

    /**
     * Edit
     * method: post
     */
    public function edit(Request $request)
    {
        // kiểm tra dữ liệu đầu vào
        $this->validate($request, [
            'category_name' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'category_name' => 'Tên',
        ]);

        try {
            // Cập nhật tên, mô tả
            DB::table('danh_muc')
                ->where('ma_danh_muc', '=', $request->category_id)
                ->update([
                    'ten_danh_muc' => $request->category_name,
                    'mo_ta' => $request->category_description,
                    'thoi_gian_cap_nhat' => date('Y-m-d H:i:s', time())
                ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.category.list', ['result' => 'success']);
    }

    /**
     * Delete
     * method: get
     */
    public function delete($category_id)
    {
        try {
            DB::table('danh_muc')->where('ma_danh_muc', '=', $category_id)->delete();
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }

        return view('admin.category.list', ['result' => 'success']);
    }
}
