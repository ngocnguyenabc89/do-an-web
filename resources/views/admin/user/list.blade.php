<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Người Dùng</h1>
</div>

<!-- Page Body -->
<div class="card">
    <div class="card-body">

        <!-- Content Row -->
        <div class="row mb-4">
            <div class="col-md-2">
                <a href="{{ url('admin/user/create') }}" class="btn btn-success">Tạo Mới</a>
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
                            <th>Họ Tên</th>
                            <th>Điện Thoại</th>
                            <th>Email</th>
                            <th>Quyền</th>
                            <th>Đăng Nhập Gần Nhất</th>
                            <th>Ngày Tạo</th>
                            <th>Ngày Cập Nhật</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $number = 1
                        @endphp

                        @if ( isset($userList) )
                        @foreach($userList as $user)
                        <tr>
                            <td>{{ $number }}</td>
                            <td>{{ $user->ma_nguoi_dung }}</td>
                            <td>
                                <image src="{{ asset("storage/user/$user->anh_nguoi_dung") }}" alt="img" width="80">
                            </td>
                            <td>{{ $user->ten_nguoi_dung }}</td>
                            <td>{{ $user->dien_thoai }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->loai == 1)
                                <span class="text-white bg-secondary p-1">Nhân Viên</span>
                                @elseif ($user->loai== 2)
                                <span class="text-white bg-success p-1">Quản Trị</span>
                                @endif
                            </td>
                            <td>{{ $user->dang_nhap_gan_nhat }}</td>
                            <td>{{ date("H:m d/m/y", strtotime($user->thoi_gian_tao)) }}</td>
                            <td>{{ date("H:m d/m/y", strtotime($user->thoi_gian_cap_nhat))  }}</td>
                            <td>
                                <a href="{{ url("admin/user/info/$user->ma_nguoi_dung") }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="{{ url("admin/user/delete/$user->ma_nguoi_dung") }}"
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
                @if (isset($userList))
                {{ $userList->links() }}
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
            title: 'Xóa người dùng này?',
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