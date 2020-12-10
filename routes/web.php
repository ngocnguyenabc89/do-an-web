<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
|--------------------------------------------------------------------------
| STORE Web Routes
|--------------------------------------------------------------------------
|
| Thiết lập đường dẫn vào trang STORE ở đây
|
*/

Route::get('/', function () {
    return view('welcome');
});






/*
|--------------------------------------------------------------------------
| ADMIN Web Routes
|--------------------------------------------------------------------------
|
| Thiết lập đường dẫn vào trang ADMIN ở đây
|
*/

// Root
Route::get('admin', 'AdminController@index'); // URL = localhost:8000/admin
Route::get('admin/login', 'AdminController@viewLogin');
Route::post('admin/login', 'AdminController@login');

/*
| Tạo route group cho Admin.
| prefix : thêm tiền tố vào đường dẫn được liệt kê bên trong hàm
| middleware: Hàm xử lý trung gian. Middleware [check.login]  kiểm tra xem người dùng có đăng nhập chưa. Nếu đăng nhập rồi mới được truy cập vào các route bên trong. Còn không chuyển hướng về trang login
*/

Route::group(['prefix' => 'admin'], function () {

    // Logout
    Route::get('logout', 'AdminController@logout');

    // Dashboard
    Route::get('dashboard', function () {
        return view('admin.dashboard.dashboard');
    });

    // User group - Middelware [check.user.permission] chỉ cho phép người Quản trị được vào route này. Nhân viên không đc truy cập
    Route::group(['prefix' => 'user'], function () {
        Route::get('list', 'UserController@viewList'); // URL = localhost:8000/admin/user/list
        Route::get('create', 'UserController@viewCreate'); // URL = localhost:8000/admin/user/create
        Route::get('info/{user_id}', 'UserController@viewInfo'); // URL = localhost:8000/admin/user/info/{user_id}

        Route::get('delete/{user_id}', 'UserController@delete'); // URL = localhost:8000/admin/user/delete/{user_id}
        Route::post('create', 'UserController@create');
        Route::post('edit', 'UserController@edit');
    });

    // Category group
    Route::group(['prefix' => 'category'], function () {
        Route::get('list', 'CategoryController@viewList'); // URL = localhost:8000/admin/category/list
        Route::get('create', 'CategoryController@viewCreate'); // URL = localhost:8000/admin/category/create
        Route::get('info/{category_id}', 'CategoryController@viewInfo'); // URL = localhost:8000/admin/category/info/{category_id}

        Route::post('create', 'CategoryController@create'); // URL = localhost:8000/admin/category/create
        Route::post('edit', 'CategoryController@edit'); // URL = localhost:8000/admin/category/edit/{category_id}
        Route::get('delete/{category_id}', 'CategoryController@delete'); // URL = localhost:8000/admin/category/delete/{category_id}
    });

    // Product group
    Route::group(['prefix' => 'product'], function () {
        Route::get('list', 'ProductController@viewList'); // URL = localhost:8000/admin/product/list
        Route::get('create', 'ProductController@viewCreate'); // URL = localhost:8000/admin/product/create
        Route::get('info/{product_id}', 'ProductController@viewInfo'); // URL = localhost:8000/admin/product/create

        Route::post('create', 'ProductController@create'); // URL = localhost:8000/admin/product/create
        Route::post('edit', 'ProductController@edit'); // URL = localhost:8000/admin/product/edit/{product_id}
        Route::get('delete/{product_id}', 'ProductController@delete'); // URL = localhost:8000/admin/product/delete/{product_id}
    });

    // Product group
    Route::group(['prefix' => 'order'], function () {
        Route::get('list', 'OrderController@viewList'); // URL = localhost:8000/admin/order/list
        Route::get('create', 'OrderController@viewCreate'); // URL = localhost:8000/admin/order/create
        Route::get('info/{order_id}', 'OrderController@viewInfo'); // URL = localhost:8000/admin/order/create

        Route::post('create', 'OrderController@create'); // URL = localhost:8000/admin/order/create
        Route::post('edit', 'OrderController@edit'); // URL = localhost:8000/admin/order/edit/{product_id}
        Route::get('delete/{product_id}', 'OrderController@delete'); // URL = localhost:8000/admin/order/delete/{product_id}
    });
});
