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
                        <h2>Đơn hàng</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Đơn hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Sale Order Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="#">
                    <div class="row">
                        <div class="col-lg-10 col-md-10">
                            <div class="checkout__order">
                                <h6 class="order__title">Đơn hàng của bạn</h6>
                                <div class="checkout__order__products">
                                    <table class="checkout__order__products">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                        <tr>
                                            <td><span>Vanilla cake</span></td>
                                            <td><span>3</span></td>
                                            <td><span>1000000</span></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <ul class="checkout__total__all">
                                    <li>Phí vận chuyển <span>90000</span></li>
                                    <li>Khuyến mãi <span>0</span></li>
                                    <li>Tổng cộng <span>190000</span></li>
                                </ul>
                                <div class="checkout__input__checkbox">
                                    <label for="confirm-order">
                                        Đã xác nhận
                                        <input type="checkbox" id="confirm-order">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="checkout__input__checkbox">
                                    <label for="confirm-receive">
                                        Đã nhận hàng
                                        <input type="checkbox" id="confirm-receive">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Sale Order Section End -->
</div>
<!-- Main Content End -->
@endsection