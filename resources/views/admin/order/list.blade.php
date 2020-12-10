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
        <div class="row mb-4">
            <div class="col-md-2">
                <a href="{{ url('admin/order/create') }}" class="btn btn-success">Tạo Mới</a>
            </div>
        </div>

        <h4>Danh Sách</h4>
        <!-- Content Row -->
        <div class="row">
            <!-- Table -->
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Ngày Tạo</th>
                            <th>Đơn Hàng</th>
                            <th>Tình Trạng</th>
                            <th>Địa Chỉ</th>
                            <th>Ngày Giao Hàng</th>
                            <th>Khách Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ( isset($orderList) )
                        @foreach($orderList as $order)
                        <tr>
                            <td>{{ date("H:m d/m/y", strtotime($order->thoi_gian_tao)) }}</td>
                            <td>{{ $order->ma_don_hang }}</td>
                            <td>
                                @if ( $order->tinh_trang == 0)
                                <span class="text-white bg-secondary p-1">Hủy</span>
                                @elseif ( $order->tinh_trang == 1)
                                <span class="text-white bg-warning p-1">Chờ Xác Nhận</span>
                                @elseif ( $order->tinh_trang == 2)
                                <span class="text-white bg-primary p-1">Đang Xử Lý</span>
                                @elseif ( $order->tinh_trang == 3)
                                <span class="text-white bg-primary p-1">Hoàn Tất</span>
                                @endif
                            </td>
                            <td>{{ $order->dia_chi_giao_hang }}</td>
                            <td>{{ date("H:m d/m/y", strtotime($order->thoi_gian_giao_hang)) }}</td>
                            <td>{{ $order->ten_khach_hang }}</td>
                            <td>{{ $order->tong_tien }}</td>
                            <td>
                                <a href="{{ url("admin/order/info/$order->ma_don_hang") }}"
                                    class="btn btn-info btn-circle btn-sm">
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
    // Kiểm tra biến result từ server gửi về để thông báo kết quả
    @if(isset($result))
    @if($result == "success")
    Swal.fire({
        icon: 'success',
        title: 'Thành Công',
        showConfirmButton: false,
        timer: 1200
    }).then((result) => {
        location.assign("{{ url('admin/order/list') }}")
    })
    @else

    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Không Thành Công!',
    })
    @endif
    @endif
</script>
@endsection