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
                        <h2>Đơn hàng</h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Đơn hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Sale Order Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form action="#">
                    <div class="row">
                        <div class="col-lg-10 col-md-10">
                            <div class="checkout__order">
                                <h6 class="order__title">Đơn đặt hàng số #{{ $order->ma_don_hang }}}</h6>
                                <h6 class="order__title">Khách Hàng: {{ $order->ten_khach_hang }}</h6>
                                <h6 class="order__title">Điện Thoại: {{ $order->dien_thoai_khach_hang }}</h6>
                                <div class="checkout__order__products">
                                    <table class="checkout__order__products">
                                        @foreach($order_detail as $product)
                                        <tr>
                                            <td>{{ $product->ten_san_pham }}</td>
                                            <td>{{ $product->don_gia }}</td>
                                            <td>{{ $product->so_luong_ban }}</td>
                                            <td class="text-right">{{ number_format($product->thanh_tien, 0, '', ',') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <ul class="checkout__total__all">
                                    <li>Tổng số lượng <span>{{ $order->tong_so_luong }}</span></li>
                                    <li>Tổng cộng <span>{{ $order->tong_tien }}</span></li>
                                </ul>
                                <div>
                                    <strong><em>Nhân Viên Của Chúng Tôi Sẽ Liên Hệ Với Bạn Trong Vòng 1 Giờ
                                            Đồng Hồ Kể Từ Thời Điểm Đặt Hàng. Trân Trọng Cảm Ơn.</em></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Sale Order Section End -->
</div>
<!-- Main Content End -->
@endsection