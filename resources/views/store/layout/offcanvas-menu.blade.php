<!-- Offcanvas Menu Begin -->
<div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__cart">
            <div class="offcanvas__cart__links">
                <a href="{{ url('/') }}" class="search-switch"><img src="{{ asset('store-assets/img/icon/search.png') }}" alt=""></a>
                <a href="{{ url('store/customer/wishlist') }}"><img src="{{ asset('store-assets/img/icon/heart.png') }}" alt=""></a>
            </div>
            <div class="offcanvas__cart__item">
                <a href="{{ url('store/customer/shopping-cart') }}"><img src="{{ asset('store-assets/img/icon/cart.png') }}" alt=""> <span>0</span></a>
                <div class="cart__price">Giỏ Hàng: <span>0</span></div>
            </div>
        </div>
        <div class="offcanvas__logo">
            <a href="{{ url('store/dashboard') }}"><img src="{{ asset('store-assets/img/logo.png') }}"  alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__option">
            <li><a href="{{ url('store/login') }}">Đăng nhập</a> <span class="arrow_carrot-down"></span></li>
        </div>
    </div>
    <!-- Offcanvas Menu End -->