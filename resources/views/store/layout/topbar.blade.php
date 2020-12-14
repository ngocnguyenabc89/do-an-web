<!-- Tính tổng tiền và tổng sản phẩm cho giỏ hàng -->
<?php
$total = 0; 
$quantity = 0;
?>
@if (Session::has('cart'))
@foreach (Session::get('cart') as $product)
<?php
            $total += $product->gia * $product->qty;
            $quantity += $product->qty;
  ?>
@endforeach
@endif


<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header__top__inner">
                        <div class="header__top__left">
                            <a href="{{ url('/') }}"><img src="{{ asset('store-assets/img/logo.png') }}" alt=""></a>
                        </div>
                        <form action="{{ url('shop/search') }}"
                            class="header__search d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100"
                            method="post">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="product_name" class="form-control bg-light border-0 small"
                                    placeholder="Nhập tên sản phẩm ...">
                                <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                        <div class="header__top__right">
                            <div class="header__top__right__cart">
                                <a href="{{ url('checkout/cart') }}" class="btn btn-link">
                                    <i class="fa fa-shopping-basket fa-2x" style="color: black;"></i>
                                    <span class="badge-pill badge-danger">{{$quantity}}</span>
                                </a>
                                <div class="cart__price">
                                    Giỏ Hàng:
                                    <span style="color: #f08632;">
                                        {{ number_format($total, 0, '', ',') }}
                                    </span>
                                </div>
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
                        <li class=""><a href="{{ url('/') }}">Trang Chủ</a></li>
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