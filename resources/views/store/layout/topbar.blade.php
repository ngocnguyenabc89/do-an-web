<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header__top__inner">
                        <div class="header__top__left">
                            <ul><li><a href="{{ url('store/login') }}">Đăng nhập</a> <span class="arrow_carrot-down"></span></li></ul>
                        </div>
                        <div class="header__logo">
                            <a href="{{ url('store/dashboard') }}"><img src="{{ asset('store-assets.img.logo.png') }}" alt=""></a>
                        </div>
                        <div class="header__top__right">
                            <div class="header__top__right__links">
                                <a href="{{ url('/') }}" class="search-switch"><img src="{{ asset('store-assets/img/icon/search.png') }}" alt=""></a>
                                <a href="{{ url('store/customer/wishlist') }}"><img src="{{ asset('store-assets/img.icon/heart.png') }}" alt=""></a>
                            </div>
                            <div class="header__top__right__cart">
                                <a href="{{ url('store/customer/shopping-cart') }}"><img src="{{ asset('store-assets/img/icon/cart.png') }}" alt=""> <span>0</span></a>
                                <div class="cart__price">Giỏ Hàng: <span>0</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class=""><a href="{{ url('store/dashboard') }}">Trang Chủ</a></li>
                        <li><a href="{{ url('store/shop') }}">Cửa Hàng</a></li>
                        <li><a href="{{ url('/') }}">Tài Khoản</a>
                            <ul class="dropdown">
                                <li><a href="{{ url('store/customer/sale-order') }}">Đơn hàng</a></li>
                                <li><a href="{{ url('store/customer/shopping-cart') }}">Giỏ hàng</a></li>
                                <li><a href="{{ url('store/customer/wishlist') }}">Yêu thích</a></li>
                                <li><a href="{{ url('store/login') }}">Đăng xuất</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- Header Section End -->