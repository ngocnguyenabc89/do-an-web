<!-- Khai báo sử dụng layout store -->
@extends('store.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout store-->
@section('main-container')
<!-- Main Content Begin -->
<div id="main-content">
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Giỏ hàng</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__pic">
                                            <img src="" alt="">
                                        </div>
                                        <div class="product__cart__item__text">
                                            <h6>Tên sản phẩm</h6>
                                            <h5>Đơn giá</h5>
                                        </div>
                                    </td>
                                    <td class="quantity__item">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="text" value="1">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cart__price">Thành tiền</td>
                                    <td class="cart__close"><a href="{{ url('store/customer/shopping-cart') }}"><span class="icon_close"></span></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container">
                        <div class="col-md-12">
                            <div class="continue__btn update__btn">
                                <a href="{{ url('store/customer/shopping-cart') }}"><i class="fa fa-spinner"></i> Cập nhật giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart__discount">
                        <h6>Mã khuyến mãi</h6>
                        <form action="#">
                            <input type="text" placeholder="Nhập mã">
                            <button type="submit">Thêm</button>
                        </form>
                    </div>
                    <div class="cart__total">
                        <h6>Giỏ hàng</h6>
                        <ul>
                            <li>Khuyến mãi <span>0</span></li>
                            <li>Tổng tiền <span>0</span></li>
                        </ul>
                        <a href="{{ url('store/customer/checkout') }}" class="primary-btn">Mua hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
</div>
<!-- Main Content End -->
@endsection