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
                        <h2>Yêu thích</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Yêu thích</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Wishlist Section Begin -->
    <section class="wishlist spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="wishlist__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Tình trạng</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__pic">
                                            <img src="" alt="">Hình SP
                                        </div>
                                        <div class="product__cart__item__text">
                                            <h6>Tên sản phẩm</h6>
                                        </div>
                                    </td>
                                    <td class="cart__price">Giá tiền</td>
                                    <td class="cart__stock">Còn/Hết hàng</td>
                                    <td class="cart__btn"><a href="{{ url('store/customer/shopping-cart') }}" class="primary-btn">Thêm vào giỏ</a></td>
                                    <td class="cart__close"><a href="{{ url('store/customer/wishlist') }}"><span class="icon_close"></span></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Wishlist Section End -->
</div>
<!-- Main Content End -->
@endsection