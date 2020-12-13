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

        return view('store.checkout.order');
    }


    /**
     * Create Order
     * method: POST
     */
    public function createOrder()
    {

        // Nếu giỏ hàng trống
        if (!Session::has('cart') || count(Session::get('cart')) == 0) {
            Redirect::to("/");
        }
    }
}
