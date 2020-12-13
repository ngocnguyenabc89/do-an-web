<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Danh Mục</h1>
</div>

<!-- Page Body -->
<div class="card">
    <div class="card-body">

        <!-- Content Row -->
        <div class="row mb-4">
            <div class="col-md-2">
                <a href="{{ url('admin/category/create') }}" class="btn btn-success">Tạo Mới</a>
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
                            <th>Tên</th>
                            <th>Mô Tả</th>
                            <th>Ngày Tạo</th>
                            <th>Ngày Cập Nhật</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $number = 1
                        @endphp

                        @if ( isset($categoryList) )
                        @foreach($categoryList as $category)
                        <tr>
                            <td>{{ $number }}</td>
                            <td>{{ $category->ma_danh_muc }}</td>
                            <td>{{ $category->ten_danh_muc }}</td>
                            <td>{{ $category->mo_ta }}</td>
                            <td>{{ date("H:m d/m/y", strtotime($category->thoi_gian_tao)) }}</td>
                            <td>{{ date("H:m d/m/y", strtotime($category->thoi_gian_cap_nhat)) }}</td>
                            <td>
                                <a href="{{ url("admin/category/info/$category->ma_danh_muc") }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="{{ url("admin/category/delete/$category->ma_danh_muc") }}"
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
                @if (isset($categoryList))
                {{ $categoryList->links() }}
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
    // Trang gốc dùng để chuyển trang khi hoàn tất thao tác
    const url_base = "{{ url('admin/category/list') }}"

    // Xác nhận trước khi xóa. btnDelete được truyền vào bằng từ khóa this trong lúc gọi hàm
    const confirmDelete = (btnDelete) => {
        Swal.fire({
            title: 'Xóa Dữ Liệu Này?',
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