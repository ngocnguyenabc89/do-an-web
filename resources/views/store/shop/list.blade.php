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
                        <h2>Cửa hàng</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Cửa hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                @foreach ($product_list as $product)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg"
                            data-setbg="{{ asset("storage/product/$product->anh_san_pham") }}"
                            style="background-image: url("{{ asset("storage/product/$product->anh_san_pham") }} "" )">
                            <div class="product__label">
                                <span>{{ $product->ten_danh_muc }}</span>
                            </div>
                        </div>
                        <div class="product__item__text">
                            <h6><a
                                    href="{{ url("shop/product-detail/$product->ma_san_pham") }}">{{ $product->ten_san_pham }}</a>
                            </h6>
                            <div class="product__item__price">{{ number_format($product->gia, 0, '', ',') }}</div>
                            <div class="cart_add">
                                <a href="{{ url("shop/add-cart/$product->ma_san_pham") }}">Thêm Giỏ Hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="shop__last__option">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        {{ $product_list->links() }}
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->
</div>
<!-- Main Content End -->
@endsection