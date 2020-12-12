<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-primary font-weight-bold mr-5">Đơn Hàng Số #{{ $order->ma_don_hang }}</h1>
        @if ( $order->tinh_trang == 0)
        <span class="text-white bg-danger p-1">Đã Hủy</span>
        @elseif ( $order->tinh_trang == 1)
        <span class="text-white bg-warning p-1">Chờ Xác Nhận</span>
        @elseif ( $order->tinh_trang == 2)
        <span class="text-white bg-primary p-1">Đã Xác Nhận</span>
        @elseif ( $order->tinh_trang == 3)
        <span class="text-white bg-success p-1">Đã Giao Hàng</span>
        @endif
    </div>
    <h5><a class="text-primary mb-5" href="{{url('admin/order/list')}}">Đơn hàng</a> / #{{ $order->ma_don_hang }}</h5>
</div>