<!-- Khai báo sử dụng layout store -->
@extends('store.layout.index')


@php
$amount_total = 0;
$quantity_total = 0;
@endphp
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
                <form action="{{ url("checkout/create-order") }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h6 class="checkout__title">Thông Tin Khách Hàng</h6>
                            <div class="checkout__input">
                                <p>Họ tên<span>*</span></p>
                                <input type="text" name="customer_name" placeholder="Nhập Tên" required>
                            </div>
                            <div class="checkout__input">
                                <p>Địa chỉ<span>*</span></p>
                                <input type="text" class="checkout__input__add" name="customer_address"
                                    placeholder="Nhập Địa Chỉ Giao Hàng" required>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Điện thoại<span>*</span></p>
                                        <input type="number" name="customer_phone" placeholder="Nhập Số Điện Thoại"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkout__input">
                                        <p>Thời Gian Giao Hàng<span>*</span></p>
                                        <input class="form-control" name="customer_time_delivery" type="datetime-local"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Ghi chú<span>*</span></p>
                                <textarea type="text" name="customer_note" class="form-control" cols="60" rows="3">
                                </textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="checkout__order">
                                <h6 class="order__title">Đơn hàng của bạn</h6>
                                <div class="checkout__order__products">
                                    <table class="checkout__order__products">
                                        @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                        @foreach(Session::get('cart') as $product)
                                        <tr>
                                            <td>{{ $product->ten_san_pham }}</td>
                                            <td>{{ $product->qty }}</td>
                                            @php
                                            $amount = $product->gia * $product->qty;
                                            $amount_total += $amount;
                                            $quantity_total += $product->qty;
                                            @endphp
                                            <td class="text-right">{{ number_format($amount, 0, '', ',') }}</td>
                                        </tr>

                                        @endforeach
                                        @endif
                                    </table>
                                </div>

                                <ul class="checkout__total__all">
                                    <li>Tổng số lượng<span>{{$quantity_total }}</span></li>
                                    <input type="number" name="quantity_total" value="{{$quantity_total }}" hidden>
                                    <li>Tổng cộng <span>{{ number_format($amount_total, 0, '', ',') }}</span></li>
                                    <input type="number" name="amount_total" value="{{$amount_total }}" hidden>
                                </ul>
                                <button type="submit" class="site-btn">Đặt Hàng</button>
                                <a href="{{ url("checkout/cancel-order") }}" class="btn site-btn bg-danger">Hủy Đơn
                                    Hàng</a>
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