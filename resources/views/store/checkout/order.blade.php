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
                        <h2>Thanh toán</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="{{ url("checkout/create-order") }}">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h6 class="checkout__title">Chi tiết đơn hàng</h6>
                            <div class="checkout__input">
                                <p>Họ tên<span>*</span></p>
                                <input type="text" name="order_customer_name" placeholder="Nhập Tên" required>
                            </div>
                            <div class="checkout__input">
                                <p>Địa chỉ<span>*</span></p>
                                <input type="text" class="checkout__input__add" name="order_customer_address"
                                    placeholder="Nhập Địa Chỉ Giao Hàng" required>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Điện thoại<span>*</span></p>
                                        <input type="number" name="order_customer_phone"
                                            placeholder="Nhập Số Điện Thoại" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Thời Gian Giao Hàng<span>*</span></p>
                                        <input class="form-control" type="datetime-local">
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Ghi chú<span>*</span></p>
                                <textarea type="text" name="order_customer_note" placeholder="Ghi chú cho đơn hàng">
                                </textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="checkout__order">
                                <h6 class="order__title">Đơn hàng của bạn</h6>
                                <div class="checkout__order__products">
                                    <table class="checkout__order__products">
                                        @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                        @php
                                        $total = 0;
                                        $total_quantity = 0;
                                        @endphp
                                        @foreach(Session::get('cart') as $product)
                                        <tr>
                                            <td>{{ $product->ten_san_pham }}</td>
                                            <td>{{ $product->qty }}</td>
                                            @php
                                            $amount = $product->gia * $product->qty;
                                            @endphp
                                            <td class="text-right">{{ number_format($amount, 0, '', ',') }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </table>
                                </div>

                                <ul class="checkout__total__all">
                                    <li>Tổng số lượng<span>{{ $total_quantity }}</span></li>
                                    <li>Tổng cộng <span>{{ number_format($total, 0, '', ',') }}</span></li>
                                </ul>
                                <button type="submit" class="site-btn">Đặt Hàng</button>
                                <a href="{{ url("checkout/cancel-order") }}" class="btn btn-danger">Hủy Đơn Hàng</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
</div>
<!-- Main Content End -->
@endsection