<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
            return view('admin.login', ['result' => 'fail', 'message' => 'Không Thành Công']);
        }

        // nếu khớp email + password thì lưu thông tin vào session
        Session::put('user_id', $user->ma_nguoi_dung);
        Session::put('user_name', $user->ten_nguoi_dung);
        Session::put('user_type', $user->loai);
        Session::put('user_image', $user->anh_nguoi_dung);
        Session::put('user_password', $user->mat_khau);


        return view('admin.login', ['result' => 'success', 'title' => 'Đăng Nhập Thành Công', 'message' => 'Đang chuyển hướng...', 'type' => 'login']);
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
