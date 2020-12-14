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
                        <h4 class="font-weight-bold">Đặt Hàng Thành Công</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="breadcrumb__links">
                        <a href="{{ url('store/dashboard') }}">Trang chủ</a>
                        <span>Đặt Hàng Thành Công</span>
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
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <p>Đơn đặt hàng số</p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="text-primary font-weight-bold">{{ $order->ma_don_hang }}</p>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <p>Tên Khách Hàng</p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="text-primary font-weight-bold">{{ $order->ten_khach_hang }}</p>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <p>Điện Thoại</p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="text-primary font-weight-bold">{{ $order->dien_thoai_khach_hang }}</p>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <p>Địa Chỉ</p>
                                    </div>
                                    <div class="col-md-8">
                                        <p class="text-primary font-weight-bold">{{ $order->dia_chi_giao_hang }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <p class="font-weight-bold">Chi Tiết Đơn Hàng</p>
                                    </div>
                                    <div class="col-md-8">
                                    </div>
                                </div>

                                <div class="checkout__order__products">
                                    <table class="checkout__order__products">
                                        <tbody>
                                            @foreach($order_detail as $product)
                                            <tr>
                                                <td>{{ $product->ten_san_pham }}</td>
                                                <td>{{ number_format($product->don_gia, 0, '', ',')}}</td>
                                                <td>{{ $product->so_luong_ban }}</td>
                                                <td class="text-right">
                                                    {{ number_format($product->thanh_tien, 0, '', ',') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <ul class="checkout__total__all">
                                    <li>Tổng số lượng <span>{{ $order->tong_so_luong }}</span></li>
                                    <li>Tổng cộng <span>{{ number_format($order->tong_tien, 0, '', ',') }}</span>
                                    </li>
                                </ul>
                                <div>
                                    <p><em class="text-danger text-center">Nhân Viên Của Chúng Tôi Sẽ Liên Hệ Với Bạn
                                            Trong
                                            Vòng 1 Giờ
                                            Đồng Hồ Kể Từ Thời Điểm Đặt Hàng. Trân Trọng Cảm Ơn.</em></p>
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