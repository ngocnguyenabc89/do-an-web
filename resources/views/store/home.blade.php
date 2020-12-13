<!-- Khai báo sử dụng layout store -->
@extends('store.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout store-->
@section('main-container')
<!-- Main Content Begin -->
<div id="main-content">
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__item set-bg" data-setbg="img/hero/hero-1.jpg">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero__text">
                                <h2>Tin tuws</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__item set-bg" data-setbg="img/hero/hero-1.jpg">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <div class="hero__text">
                                <h2>Giải tỏa mọi căng thẳng bằng những thức uống yêu thích!</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Section Begin -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="about__text">
                        <div class="section-title">
                            <span>Về 3Gs House</span>
                            <h2>Vị ngọt cho một ngày mới!</h2>
                        </div>
                        <p>Ngôi nhà nhỏ "3Gs House" được thành lập từ những cô gái có niềm đam mê to lớn với những chiếc bánh ngọt ngào. Hãy đến với cửa hàng để bắt đầu một ngày đẹp của bạn.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <img src="{{ asset('store-assets/img/image-about.jpg') }}">
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->


    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
            <!-- 1 sản phẩm -->
            @foreach
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ asset('store-assets/img/') }}">
                            <div class="product__label">
                                <span>Loại sản phẩm</span>
                            </div>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="{{ url('store/shop-detail') }}">Tên sản phẩm</a></h6>
                            <div class="product__item__price">Giá</div>
                            <div class="cart_add">
                                <a href="{{ url('store/customer/shopping-cart') }}">Thêm vào giỏ</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </section>
    <!-- Product Section End -->
</div>
<!-- Main Content End -->
@endsection