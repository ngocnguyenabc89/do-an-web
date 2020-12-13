<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Đơn Hàng</h1>
</div>

<!-- Page Body -->
<div class="card">
    <div class="card-body">

        <!-- Content Row -->
        <h4>Danh Sách</h4>
        <!-- Content Row -->
        <div class="row">
            <!-- Table -->
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tình Trạng</th>
                            <th>Ngày Đặt Hàng</th>
                            <th>Đơn Hàng</th>
                            <th>Khách Hàng</th>
                            <th>Địa Chỉ</th>
                            <th>Ngày Giao Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ( isset($orderList) )
                        @foreach($orderList as $order)
                        <tr>
                            <td>
                                @if ( $order->tinh_trang == 0)
                                <span class="text-white bg-secondary p-1">hủy</span>
                                @elseif ( $order->tinh_trang == 1)
                                <span class="text-white bg-warning p-1">đang chờ</span>
                                @elseif ( $order->tinh_trang == 2)
                                <span class="text-white bg-primary p-1">xác nhận</span>
                                @elseif ( $order->tinh_trang == 3)
                                <span class="text-white bg-success p-1">thành công</span>
                                @endif
                            </td>
                            <td class="font-weight-bold">{{ date("H:m d/m/y", strtotime($order->thoi_gian_tao)) }}</td>
                            <td class="text-center">{{ $order->ma_don_hang }}</td>
                            <td>{{ $order->ten_khach_hang }}</td>
                            <td>{{ $order->dia_chi_giao_hang }}</td>
                            <td class="text-primary font-weight-bold">
                                {{ date("H:m d/m/y", strtotime($order->thoi_gian_giao_hang)) }}
                            </td>
                            <td class="text-right text-danger font-weight-bold">
                                {{ number_format( $order->tong_tien, 0, '', ',') }}</td>
                            <td class="text-center">
                                <a href="{{ url("admin/order/info/$order->ma_don_hang") }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @if (isset($orderList))
                {{ $orderList->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
<!-- kết thúc main-container -->
@endsection
<!-- Javascript -->
@section('script')
<script>
    // Xác nhận trước khi xóa. btnDelete được truyền vào bằng từ khóa this trong lúc gọi hàm
    const confirmDelete = (btnDelete) => {
        Swal.fire({
            title: 'Xóa Sản Phẩm này?',
            text: "Bạn không thể khôi phục sau khi xóa",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                location.assign(btnDelete.href)
            }
            return false
        })
        return false
    }
</script>
@endsection