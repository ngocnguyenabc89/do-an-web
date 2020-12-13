<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header__top__inner">
                        <div class="header__logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('store-assets/img/logo.png') }}" alt=""></a>
                        </div>
                        <div class="header__top__right">
                            <div class="header__top__right__links">
                                <a href="{{ url('/') }}" class="search-switch"><img
                                        src="{{ asset('store-assets/img/icon/search.png') }}" alt=""></a>
                            </div>
                            <div class="header__top__right__cart">
                                <a href="{{ url('store/customer/shopping-cart') }}"><img
                                        src="{{ asset('store-assets/img/icon/cart.png') }}" alt=""> <span>0</span></a>
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
                        <li class=""><a href="{{ url('home') }}">Trang Chủ</a></li>
                        <li><a href="{{ url('shop') }}">Cửa Hàng</a>
                            <ul class="dropdown">
                                @foreach ($category_list as $category)
                                <li><a
                                        href="{{ url("/shop/category/$category->ma_danh_muc") }}">{{$category->ten_danh_muc}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- Header Section End -->