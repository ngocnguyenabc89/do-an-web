<!-- Khai báo sử dụng layout store -->
@extends('store.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout store-->
@section('main-container')

<div id="main-content">
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__text">
                        <h2>Sản phẩm</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url("/")}}">Trang chủ</a>
                        <a href="{{ url("/shop") }}">Cửa hàng</a>
                        <span>{{ $product->ten_san_pham}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product__details__img">
                        <div class="product__details__big__img">
                            <img class="big_img" src="{{ asset("storage/product/$product->anh_san_pham") }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <div class="product__label">{{ $product->ten_danh_muc }}</div>
                        <h4>{{ $product->ten_san_pham }}</h4>
                        <h5>{{ number_format($product->gia, 0, '', ',') }}</h5>
                        <div>
                            {!! $product->mo_ta_san_pham !!}
                        </div>
                        <form action="{{ url("checkout/add-cart") }}" method="POST">
                            @csrf
                            <input type="number" name="product_id" value="{{ $product->ma_san_pham }}" hidden>
                            <div class="product__details__option">
                                <div class="quantity">
                                    <input type="number" name="product_quantity"
                                        class="font-weight-bold text-center form-control" value="1" min="1">
                                </div>
                                <button class="btn btn-success p-2">Thêm Giỏ Hàng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <!-- Related Products Section Begin -->
    <section class="related-products spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Sản phẩm khác</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="related__products__slider owl-carousel">
                    @foreach ($product_list as $product)
                    <div class="col-lg-3">
                        <div class="product__item">
                            <div class="product__item__pic set-bg"
                                data-setbg="{{ asset("storage/product/$product->anh_san_pham") }}">
                                <div class="product__label">
                                    <span>{{ $product->ten_danh_muc }}</span>
                                </div>
                            </div>
                            <div class="product__item__text">
                                <h6><a
                                        href="{{ url("shop/product/$product->ma_san_pham") }}">{{ $product->ten_san_pham }}</a>
                                </h6>
                                <div class="product__item__price">{{ number_format($product->gia, 0, '', ',') }}</div>
                                <div class="cart_add">
                                    <a href="{{ url("checkout/add-cart/$product->ma_san_pham") }}">Thêm Giỏ Hàng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    </section>
    <!-- Related Products Section End -->
</div>
@endsection