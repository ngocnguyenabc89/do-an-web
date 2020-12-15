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
        // Tổng tiền tháng này
        $first_day_this_month = date('Y-m-d', strtotime('first day of this month'));
        $last_day_this_month = date('Y-m-d', strtotime('last day of this month'));

        $earn_this_month = DB::table('don_hang')
            ->where('tinh_trang', 3)
            ->whereBetween('thoi_gian_tao', array($first_day_this_month, $last_day_this_month))
            ->sum('tong_tien');

        // Tổng tiền năm này
        $first_day_this_year = date('Y-m-d', strtotime('first day of this year'));
        $last_day_this_year = date('Y-m-d', strtotime('last day of this year'));

        $earn_this_year = DB::table('don_hang')
            ->where('tinh_trang', 3)
            ->whereBetween('thoi_gian_tao', array($first_day_this_year, $last_day_this_year))
            ->sum('tong_tien');

        // Số lượng sản phẩm bán ra
        $number_product_sale = DB::table('chi_tiet_don_hang')
            ->join('don_hang', 'don_hang.ma_don_hang', 'chi_tiet_don_hang.ma_don_hang')
            ->whereBetween('thoi_gian_tao', array($first_day_this_month, $last_day_this_month))
            ->sum('chi_tiet_don_hang.so_luong_ban');

        // Đơn hàng chờ xác nhận
        $number_order_pending = DB::table('don_hang')
            ->where('tinh_trang', 1)
            ->count('ma_don_hang');

        // Mảng doanh thu 12 tháng trong năm
        $this_year = date('Y');
        $earn_month_list = [];
        $month = [];


        for ($i = 1; $i <= 12; $i++) {
            $first_day_of_month = date($this_year . '-m-d', strtotime('first day of' . $i . 'month'));
            $last_day_of_month = date($this_year . '-m-d', strtotime('last day of' . $i . 'month'));

            $earn_month = DB::table('don_hang')
                ->where('tinh_trang', 3)
                ->whereBetween('thoi_gian_tao', array($first_day_of_month, $last_day_of_month))
                ->sum('tong_tien');

            array_push($earn_month_list, $earn_month);
            array_push($month, [$first_day_of_month, $last_day_of_month]);
        }

        // Tỷ lệ phần trăm tình trạng đơn hàng
        $order_total = DB::table('don_hang')
            ->whereBetween('thoi_gian_tao', array($first_day_this_year, $last_day_this_year))
            ->count('ma_don_hang');

        $order_cancel =  DB::table('don_hang')
            ->whereBetween('thoi_gian_tao', array($first_day_this_year, $last_day_this_year))
            ->where('tinh_trang', 0)
            ->count('ma_don_hang');

        $order_pending =  DB::table('don_hang')
            ->whereBetween('thoi_gian_tao', array($first_day_this_year, $last_day_this_year))
            ->where('tinh_trang', 1)
            ->count('ma_don_hang');

        $order_confirm =  DB::table('don_hang')
            ->whereBetween('thoi_gian_tao', array($first_day_this_year, $last_day_this_year))
            ->where('tinh_trang', 2)
            ->count('ma_don_hang');

        if ($order_cancel == 0) {

            $order_cancel_percent = 0;
        } else {
            $order_cancel_percent = floor($order_cancel / $order_total * 100);
        }

        if ($order_pending == 0) {
            $order_pending_percent = 0;
        } else {
            $order_pending_percent = floor($order_pending / $order_total * 100);
        }

        if ($order_confirm == 0) {
            $order_confirm_percent = 0;
        } else {
            $order_confirm_percent = floor($order_confirm / $order_total * 100);
        }

        $order_success_percent = 100 - ($order_cancel_percent + $order_pending_percent + $order_confirm_percent);

        $order_percent_list = [$order_cancel_percent, $order_pending_percent, $order_confirm_percent, $order_success_percent];

        return view('admin.dashboard', ['earn_this_month' => $earn_this_month, 'earn_this_year' => $earn_this_year, 'number_product_sale' => $number_product_sale, 'number_order_pending' => $number_order_pending, 'earn_month_list' => $earn_month_list, "order_percent_list" => $order_percent_list]);
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
     * bcrypt
     */
    public function login(Request $request)
    {
        // kiểm tra người dùng có tồn tại ??
        $user = DB::table('nguoi_dung')->where('email', '=', $request->user_email)->first();

        if ($user == null) {
            Session::flash('fail', 'Đăng nhập không thành công');
            return Redirect::back();
        }

        // kiểm tra mật khẩu có đúng ??
        $comparePassword = Hash::check($request->user_password, $user->mat_khau);

        if ($comparePassword == false) {
            Session::flash('fail', 'Đăng nhập không thành công');
            return Redirect::back();
        }

        // nếu khớp email + password thì lưu thông tin vào session
        // session là biến toàn cục. put là tồn tại cho đến khi xóa. flash là gọi 1 lần tự động xóa
        Session::put('user_id', $user->ma_nguoi_dung);
        Session::put('user_name', $user->ten_nguoi_dung);
        Session::put('user_type', $user->loai);
        Session::put('user_image', $user->anh_nguoi_dung);
        Session::put('user_password', $user->mat_khau);

        Session::flash('success', 'Đăng nhập thành công');

        // Lưu thời gian đăng nhập vào db
        DB::table('nguoi_dung')->where('ma_nguoi_dung', $user->ma_nguoi_dung)->update(['dang_nhap_gan_nhat' => date("Y-m-d H:i:s")]);
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

        Session::flash('success', 'Đăng Xuất Thành Công');
        return Redirect::to('admin/login');
    }
}
