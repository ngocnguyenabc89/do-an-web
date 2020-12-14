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
                        <a href="{{ url('/') }}">Trang chủ</a>
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
                                @php
                                $total = 0;
                                $total_quantity = 0;
                                @endphp
                                @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                <form id="form_update_cart" action="{{ url("checkout/update-cart") }}" method="POST">
                                    @csrf
                                    @foreach (Session::get('cart') as $product)
                                    <tr>
                                        <input type="number" name="product_id_list[]"
                                            value="{{ $product->ma_san_pham }}" hidden>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__pic">
                                                <img src="{{ asset("storage/product/$product->anh_san_pham") }}"
                                                    alt="{{ $product->ten_san_pham }}" width="100">
                                            </div>
                                            <div class="product__cart__item__text">
                                                <h6>{{ $product->ten_san_pham }}</h6>
                                                <h5>{{ number_format($product->gia, 0, '', ',') }}</h5>
                                            </div>
                                        </td>
                                        <td class="quantity__item">
                                            <div class="quantity">
                                                <div class="pro-qty text-right">
                                                    <input type="number" name="product_quantity_list[]"
                                                        class="form-control border text-center"
                                                        value="{{ $product->qty }}" min="0">
                                                </div>
                                            </div>
                                        </td>
                                        @php
                                        $amount = $product->gia * $product->qty;
                                        $total += $amount;
                                        $total_quantity += $product->qty;
                                        @endphp
                                        <td class="cart__price">{{number_format($amount, 0, '', ',') }}</td>
                                    </tr>
                                    @endforeach
                                </form>
                                @else
                                <tr>
                                    <td class="font-weight-bold">Giỏ hàng trống</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="container">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success" form="form_update_cart">Cập Nhật Giỏ
                                Hàng</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart__total">
                        <h6>Giỏ hàng</h6>
                        <ul>
                            <li>Số Lượng <span>{{ $total_quantity }}</span></li>
                            <li>Tổng tiền <span>{{ number_format($total, 0, '', ',') }}</span></li>
                        </ul>
                        @if ($total_quantity > 0)
                        <a href="{{ url('checkout/order') }}" class="primary-btn">Đặt Hàng</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
</div>
<!-- Main Content End -->
@endsection