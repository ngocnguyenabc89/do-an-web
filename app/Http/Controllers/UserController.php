<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    /**
     * Danh sách
     * method: get
     */
    public function viewList()
    {
        $userList = DB::table('nguoi_dung')->paginate(5);
        return view('admin.user.list', ['userList' => $userList]);
    }

    /**
     * view Tạo
     * method: get
     */
    public function viewCreate()
    {
        return view('admin.user.create');
    }

    /**
     * Tạo
     * method: post
     */
    public function create(Request $request)
    {
        // kiểm tra dữ liệu đầu vào
        $this->validate($request, [
            'user_name' => 'required',
            'user_phone' => 'required',
            'user_image' => 'required',
            'user_email' => 'required',
            'user_password' => 'required',
        ], [
            'required' => ':attribute không để trống'
        ], [
            'user_name' => 'Tên',
            'user_phone' => 'Điện thoại',
            'user_image' => 'Ảnh đại diện',
            'user_email' => 'Email',
            'user_password' => 'Mật khẩu',
        ]);

        try {
            // Kiểm tra email đã tồn tại chưa
            $users = DB::table('nguoi_dung')->where('email', '=', $request->user_email)->get();

            if (count($users) > 0) {
                $messageBag = new MessageBag;
                return view('admin.user.create', ["result" => "fail", "message" => "Email đã tồn tại"]);
            }

            // Lưu ảnh vô thư mục user : public/storage/user/<image_name>
            if ($request->hasFile('user_image')) {
                $imagePath = Storage::putFile('user', $request->file('user_image')); // trả về đường dẫn
                $imageName = basename(($imagePath)); // trả về tên file
            }

            // Lưu vào DB
            DB::table('nguoi_dung')->insert([
                'ten_nguoi_dung' => $request->user_name,
                'dien_thoai' => $request->user_phone,
                'email' => $request->user_email,
                'mat_khau' => bcrypt($request->user_password),
                'loai' => $request->user_permission,
                'anh_nguoi_dung' => $imageName
            ]);
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }
        Session::flash('success', 'Đã tạo người dùng');
        return Redirect::to('/admin/user/list');
    }

    /**
     * Info
     * method: get
     */
    public function viewInfo($user_id)
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

        return view('admin.user.info', ['user' => $user]);
    }

    /**
     * Edit
     * method: post
     */
    public function edit(Request $request)
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

            // Cập nhật tên, điện thoại, quyền người dùng
            DB::table('nguoi_dung')
                ->where('ma_nguoi_dung', '=', $request->user_id)
                ->update([
                    'ten_nguoi_dung' => $request->user_name,
                    'dien_thoai' => $request->user_phone,
                    'loai' => $request->user_permission,
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
     * Delete
     * method: get
     */
    public function delete($user_id)
    {
        try {
            // Kiểm tra tự xóa chính mình
            if (Session::get('user_id') == $user_id) {
                Session::flash('fail', 'Không thể tự xóa chính mình');
                return Redirect::back();
            }

            // Kiểm tra người dùng có thao tác trên đơn hàng nào hay không
            $user_order = DB::table('don_hang')->where('nhan_vien_cap_nhat', $user_id)->count('nhan_vien_cap_nhat');

            if ($user_order > 0) {
                Session::flash('fail', 'Không thể xóa nhân viên đã từng thao tác trên đơn hàng');
                return Redirect::back();
            }

            // Lấy đường dẫn ảnh trong db
            $user = DB::table('nguoi_dung')->where('ma_nguoi_dung', '=', $user_id)->first();
            $oldImagePath = $user->anh_nguoi_dung;

            // Xóa ảnh cũ
            File::delete('storage/user/' . $oldImagePath);

            // Xóa người dùng trong db
            DB::table('nguoi_dung')->where('ma_nguoi_dung', '=', $user_id)->delete();
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }

        Session::flash('success', 'Đã xóa người dùng');
        return Redirect::back();
    }
}
