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
use Illuminate\Support\Facades\View;

Session::start();

class CartController extends Controller
{
    /**
     * Contructor - Khởi tạo thông tin chung cho route Store
     */
    public function __construct()
    {
        $category_list = DB::table('danh_muc')->get();
        View::share('category_list', $category_list);
    }
    /**
     * Add Cart
     * method: get
     */
    public function addCart($product_id)
    {
        // dd($product_id);
        try {
            $product = DB::table('san_pham')->where('ma_san_pham', $product_id)->first();

            if ($product == null) {
                Redirect::back();
            }
        } catch (Exception $ex) {
            Redirect::back();
        }

        // Tạo giỏ hàng


        // Kiểm tra giỏ hàng tồn tại ?
        // dd(Session::has('cart'));

        if (Session::has('cart')) {
            $cart = Session::get('cart');
            // dd($cart[0]);
            // Tìm trong giỏ hàng xem sản phẩm đã tồn tại? nếu tồn tại + 1, nếu chưa thì thêm mới
            $isExists = false;
            $size = count($cart);
            for ($i = 0; $i < $size; $i++) {

                if ($cart[$i]->ma_san_pham == $product_id) {
                    // dd($cart[$i]);
                    $cart[$i]->qty += 1;
                    $isExists = true;
                    break;
                }
            }

            if ($isExists == false) {
                $product->qty = 1;
                array_push($cart, $product);
            }

            Session::put('cart', $cart);
        } else {
            $product->qty = 1;
            Session::put('cart', [$product]);
        }

        return Redirect::back();
    }


    /**
     * Add Cart With Quantity
     * method: post
     */
    public function addCartWithQuantity(Request $request)
    {

        // dd($request->all());
        $product_id = $request->product_id;
        $product_quantity = $request->product_quantity;

        // Lấy thông tin sản phẩm
        try {
            $product = DB::table('san_pham')->where('ma_san_pham', $product_id)->first();

            if ($product == null) {
                Redirect::back();
            }
        } catch (Exception $ex) {
            Redirect::back();
        }

        // Kiểm tra giỏ hàng đã tồn tại??
        if (Session::has('cart')) {

            // lấy giỏ hàng hiện tại đang có trong session ra
            $cart = Session::get('cart');

            // dd($cart[0]);

            // Tìm trong giỏ hàng xem sản phẩm đã tồn tại? nếu tồn tại + thêm số lượng lên, nếu chưa thì thêm mới
            $isExists = false;
            $size = count($cart);
            for ($i = 0; $i < $size; $i++) {

                if ($cart[$i]->ma_san_pham == $product_id) {
                    // dd($cart[$i]);
                    $cart[$i]->qty += $product_quantity;
                    $isExists = true;
                    break;
                }
            }

            // Nếu trong giỏ hàng chưa có sản phẩm thì thêm sản phẩm vào mảng card
            if ($isExists == false) {
                $product->qty = $product_quantity;
                array_push($cart, $product);
            }

            Session::put('cart', $cart);
        } else {
            $product->qty = $product_quantity;
            Session::put('cart', [$product]);
        }

        return Redirect::back();
    }


    /**
     * Cart
     * method:get
     */

    public function cart()
    {
        return view('store.checkout.cart');
    }


    /**
     * Update Cart With Quantity
     * method: post
     */
    public function updateCart(Request $request)
    {

        // dd($request->all());

        $product_id_list = $request->product_id_list;
        $product_quantity_list = $request->product_quantity_list;

        // lấy giỏ hàng hiện tại đang có trong session ra
        $cart = Session::get('cart');
        $newCart = [];
        $size = count($product_id_list);

        for ($i = 0; $i < $size; $i++) {
            $product_id = (int)$product_id_list[$i];
            $product_quantity = (int)$product_quantity_list[$i];

            for ($j = 0; $j < count($cart); $j++) {

                if ($cart[$j]->ma_san_pham == $product_id) {

                    if ($product_quantity == 0) {
                        break;
                    } else {
                        $cart[$j]->qty = $product_quantity;
                        array_push($newCart, $cart[$j]);
                        break;
                    }
                }
            }
        }

        // Cập nhật giỏ hàng mới vào session
        Session::put('cart', $newCart);

        return Redirect::back();
    }

    /**
     * Order
     * method: get
     */

    public function order()
    {
        // Nếu giỏ hàng trống
        if (!Session::has('cart') || count(Session::get('cart')) == 0) {
            return Redirect::to("/");
        }
        return view('store.checkout.order');
    }


    /**
     * Create Order
     * method: POST
     */
    public function createOrder(Request $request)
    {

        // Nếu giỏ hàng trống
        if (!Session::has('cart') || count(Session::get('cart')) == 0) {
            return Redirect::to("/");
        }

        // Nếu tồn tại giỏ hàng thì lấy thông tin khách hàng gửi lên
        $customer_name = $request->customer_name;
        $customer_address = $request->customer_address;
        $customer_phone = $request->customer_phone;
        $customer_time_delivery = explode("T", $request->customer_time_delivery)[0] . " " . explode("T", $request->customer_time_delivery)[1];
        // dd($customer_time_delivery);
        $customer_note = $request->customer_note;
        $amount_total = $request->amount_total;

        $quantity_total = $request->quantity_total;
        $textHistory = date("H:m:s d/m/y") . " : ĐƠN HÀNG ĐƯỢC TẠO\r\n";



        try {
            // Lưu đơn hàng vào db
            $order_id_insert = DB::table('don_hang')
                ->insertGetId([
                    'ten_khach_hang' => $customer_name,
                    'dien_thoai_khach_hang' => $customer_phone,
                    'dia_chi_giao_hang' => $customer_address,
                    'thoi_gian_giao_hang' => $customer_time_delivery,
                    'ghi_chu_khach_hang' => $customer_note,
                    'tong_tien' => $amount_total,
                    'tong_so_luong' => $quantity_total,
                    'tinh_trang' => 1,
                    'lich_su' => $textHistory,
                ]);

            // Lấy giỏ hàng từ session
            $cart = Session::get('cart');
            // dd($cart);

            // Lưu chi tiết đơn hàng
            foreach ($cart as $product) {

                DB::table('chi_tiet_don_hang')
                    ->insert([
                        'ma_don_hang' => $order_id_insert,
                        'ma_san_pham' => $product->ma_san_pham,
                        'so_luong_ban' => $product->qty,
                        'don_gia' => $product->gia,
                        'thanh_tien' => $product->qty * $product->gia
                    ]);
            }

            // Làm rỗng giỏ hàng trong session
            Session::put('cart', null);

            // Lấy thông tin đơn hàng 
            $order = DB::table('don_hang')->where('ma_don_hang', $order_id_insert)->first();
            $order_detail = DB::table('chi_tiet_don_hang')
                ->join('san_pham', 'san_pham.ma_san_pham', 'chi_tiet_don_hang.ma_san_pham')
                ->where('ma_don_hang', $order_id_insert)->get();
        } catch (Exception $ex) {
            Session::flash('fail', $ex->getMessage());
            return Redirect::back();
        }

        return view('store.checkout.order-complete', ['order' => $order, 'order_detail' => $order_detail]);
    }

    /**
     * Cancel Order
     * method: get
     */
    public function cancelOrder()
    {
        // Nếu giỏ hàng trống
        if (!Session::has('cart') || count(Session::get('cart')) == 0) {
            return Redirect::to("/");
        }

        // Xóa giỏ hàng
        Session::put('cart', null);
        return Redirect::to('/');
    }
}
