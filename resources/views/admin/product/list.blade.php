<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Sản Phẩm</h1>
</div>

<!-- Page Body -->
<div class="card">
    <div class="card-body">

        <!-- Content Row -->
        <div class="row mb-4">
            <div class="col-md-2">
                <a href="{{ url('admin/product/create') }}" class="btn btn-success">Tạo Mới</a>
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
                            <th>#</th>
                            <th>Mã</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Giá Bán</th>
                            <th>Tình Trạng</th>
                            <th>Phân Loại</th>
                            <th>Danh Mục</th>
                            <th>Ngày Tạo</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $number = 1
                        @endphp

                        @if ( isset($productList) )
                        @foreach($productList as $product)
                        <tr>
                            <td>{{ $number }}</td>
                            <td>{{ $product->ma_san_pham }}</td>
                            <td>
                                <image src="{{ asset("storage/product/$product->anh_san_pham") }}" alt="img" width="80">
                            </td>
                            <td>{{ $product->ten_san_pham }}</td>
                            <td class="text-right font-weight-bold text-primary">
                                {{ number_format( $product->gia, 0, '', ',') }}</td>
                            <td>
                                @if ( $product->tinh_trang == 0)
                                <span class="text-white bg-secondary p-1">tạm ngưng</span>
                                @elseif ( $product->tinh_trang == 1)
                                <span class="text-white bg-success p-1">mở bán</span>
                                @endif
                            </td>
                            <td>
                                @if ( $product->phan_loai == 0)
                                <span class="text-white bg-secondary p-1">thường</span>
                                @elseif ($product->phan_loai == 1)
                                <span class="text-white bg-warning p-1">nổi bật</span>
                                @endif
                            </td>
                            <td>{{ $product->ten_danh_muc }}</td>
                            <td>{{ date("H:m d/m/y", strtotime($product->thoi_gian_tao)) }}</td>
                            <td>
                                <a href="{{ url("admin/product/info/$product->ma_san_pham") }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="{{ url("admin/product/delete/$product->ma_san_pham ") }}"
                                    class="btn btn-danger btn-circle btn-sm btn-delete"
                                    onclick="return confirmDelete(this)">
                                    <i class=" fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php
                        $number++
                        @endphp
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @if (isset($productList))
                {{ $productList->links() }}
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