<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class StoreController extends Controller
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
     * home - Trang Chủ
     * method: get
     */

    public function home()
    {
        try {
            $product_list = DB::table('san_pham')
                ->join('danh_muc', 'danh_muc.ma_danh_muc', '=', 'san_pham.ma_danh_muc')
                ->where('san_pham.phan_loai', 1)
                ->get();
        } catch (Exception $ex) {
            Redirect::to('/');
        }
        return view('store.home', ['product_list' => $product_list]);
    }


    /**
     * Shop - Cửa Hàng
     * method: get
     */

    public function shop()
    {


        try {

            // Lấy sản phẩm đang mở bán
            $product_list = DB::table('san_pham')
                ->join('danh_muc', 'danh_muc.ma_danh_muc', 'san_pham.ma_danh_muc')
                ->where('san_pham.tinh_trang', 1)
                ->paginate(20);
        } catch (Exception $ex) {
            Redirect::to('/');
        }

        return view('store.shop.list', ['product_list' => $product_list]);
    }


    /**
     * Product List Of Category
     * method: get
     */
    public function productListOfCategory($category_id)
    {
        try {
            // Kiểm tra caetegory có tồn tại??
            $category = DB::table('danh_muc')->where('ma_danh_muc', $category_id)->first();

            if ($category == null) {

                return Redirect::back();
            }

            $product_list_of_category = DB::table('danh_muc')
                ->join('san_pham', 'san_pham.ma_danh_muc', '=', 'danh_muc.ma_danh_muc')
                ->where('danh_muc.ma_danh_muc', $category_id)
                ->paginate(20);
        } catch (Exception $ex) {
            return Redirect::back();
        }

        return view('store.shop.category', ['category' => $category, 'product_list_of_category' => $product_list_of_category]);
    }

    /**
     * Product Info
     * method: get
     */
    public function productInfo($product_id)
    {

        try {
            $product = DB::table('san_pham')
                ->join('danh_muc', 'danh_muc.ma_danh_muc', 'san_pham.ma_danh_muc')
                ->where('ma_san_pham', $product_id)
                ->first();

            if ($product == null) {
                return Redirect::back();
            }

            $product_list = DB::table('san_pham')
                ->join('danh_muc', 'danh_muc.ma_danh_muc', 'san_pham.ma_danh_muc')
                ->where([['ma_san_pham', '<>', $product_id], ['san_pham.ma_danh_muc', '=', $product->ma_danh_muc]])
                ->get();
            // dd($product_list);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return Redirect::back();
        }
        return view('store.shop.product', ['product' => $product, 'product_list' => $product_list]);
    }


    /**
     * Search
     * method: post
     */

    public function search(Request $request)
    {
        // dd($request->all());

        try {
            $product_name = mb_strtolower($request->product_name);
            // $product_name = 'Bánh Chocolate';
            $query = 'SELECT * FROM san_pham JOIN danh_muc ON san_pham.ma_danh_muc = danh_muc.ma_danh_muc WHERE LOWER(ten_san_pham) LIKE "%' . $product_name . '%"';
            // dd($query);
            // dd($product_name);
            $product_list = DB::select($query);
        } catch (Exception $ex) {
            return Redirect::back();
        }

        return view('store.shop.search', ['key_word' => $request->product_name, 'product_list' => $product_list]);
    }
}
