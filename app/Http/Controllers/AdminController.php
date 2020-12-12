<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// Bật Session 
session_start();

class AdminController extends Controller
{
    /**
     * Index - điều hướng trang quản trị
     * Chưa đăng nhập: chuyển đến trang login
     * Đã đăng nhập: chuyển đến trang dashboard
     * method: get
     */
    public function index()
    {
        return Redirect('admin/dashboard');
    }

    /**
     * Dashboard
     * method: get
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Personal Info
     * method: get
     */
    public function personalInfo($user_id)
    {

        try {
            $user = DB::table('nguoi_dung')->where('ma_nguoi_dung', '=', $user_id)->first();
            if ($user == null) {
                return view('admin.user.list', ['result' => 'fail', 'message' => 'Không tồn tại']);
            }
        } catch (Exception $ex) {

            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }

        return view('admin.personal-info', ['user' => $user]);
    }

    /**
     * Edit Personal Info
     * method: post
     */
    public function editPersonalInfo(Request $request)
    {
        // kiểm tra dữ liệu đầu vào
        $this->validate($request, [
            'user_name' => 'required',
            'user_phone' => 'required',
            'user_email' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'user_name' => 'Tên',
            'user_phone' => 'Điện thoại',
            'user_email' => 'Email',
        ]);

        if ($request->user_id != Session::get('user_id')) {
            Redirect::to('admin/');
        }

        try {
            // Cập nhật image
            if ($request->has('user_image')) {

                // Lấy đường dẫn ảnh trong db
                $user = DB::table('nguoi_dung')->where('ma_nguoi_dung', '=', $request->user_id)->first();
                $oldImagePath = $user->anh_nguoi_dung;

                // Xóa ảnh cũ
                File::delete('storage/user/' . $oldImagePath);

                // lưu ảnh vào thư mục
                $imagePath = Storage::putFile('user', $request->user_image);
                $imageName = basename($imagePath);

                // cập nhật ảnh trong db
                DB::table('nguoi_dung')->where('ma_nguoi_dung', '=', $request->user_id)->update(['anh_nguoi_dung' => $imageName]);
            }

            // Cập nhật mật khẩu
            if ($request->has('is_change_password')) {
                DB::table('nguoi_dung')->where('ma_nguoi_dung', '=', $request->user_id)->update(['mat_khau' => bcrypt($request->new_password)]);
            }

            // Cập nhật tên, điện thoại
            DB::table('nguoi_dung')
                ->where('ma_nguoi_dung', '=', $request->user_id)
                ->update([
                    'ten_nguoi_dung' => $request->user_name,
                    'dien_thoai' => $request->user_phone,
                    'thoi_gian_cap_nhat' => date('Y-m-d H:i:s', time())
                ]);
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }

        Session::flash('success', 'Đã cập nhật người dùng');
        return Redirect::back();
    }

    /**
     * Giao dien login
     * method: get
     */

    public function viewLogin()
    {
        return view('admin.login');
    }

    /**
     * Login
     * method: post
     */
    public function login(Request $request)
    {
        // kiểm tra người dùng có tồn tại ??
        $user = DB::table('nguoi_dung')->where('email', '=', $request->user_email)->first();

        if ($user == null) {
            return view('admin.login', ['result' => 'fail', 'message' => 'Không Thành Công']);
        }

        // kiểm tra mật khẩu có đúng ??
        $comparePassword = Hash::check($request->user_password, $user->mat_khau);

        if ($comparePassword == false) {
            Session::flash('fail', 'Đăng nhập không thành công');
            return Redirect::back();
        }

        // nếu khớp email + password thì lưu thông tin vào session
        Session::put('user_id', $user->ma_nguoi_dung);
        Session::put('user_name', $user->ten_nguoi_dung);
        Session::put('user_type', $user->loai);
        Session::put('user_image', $user->anh_nguoi_dung);
        Session::put('user_password', $user->mat_khau);

        Session::flash('success', 'Đăng nhập thành công');

        // Lưu thời gian đăng nhập vào db
        DB::table('nguoi_dung')->where('ma_nguoi_dung', $user->ma_nguoi_dung)->update(['dang_nhap_gan_nhat' => date("Y-m-d H:m:s")]);
        return Redirect::to('admin/');
    }

    /**
     * Logout
     * method: get
     */
    public function logout()
    {
        Session::put('user_id', null);
        Session::put('user_name', null);
        Session::put('user_type', null);
        Session::put('user_image', null);
        Session::put('user_password', null);

        Session::put('logout_message', 'Đăng Xuất Thành Công');
        return Redirect::to('admin/login');
        // return view('admin.login', ['result' => 'success', 'title' => 'Đăng Xuất Thành Công', 'message' => 'Đăng nhập để tiếp tục', 'type' => 'logout']);
    }
}
