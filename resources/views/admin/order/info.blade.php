<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Đơn Hàng Số #</h1>
    <h5><a class="text-primary mb-5" href="{{url('admin/product/list')}}">Sản phẩm</a> / Sửa</h5>
</div>
<!-- Page Body -->
<div class="row">
    {{-- Cột Bên trái --}}
    <div class="col-md-9">

        {{-- Chi Tiết Đơn Hàng --}}
        <div class="card">
            <div class="card-body">
                <h5>Chi Tiết Đơn Hàng</h5>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã Sản Phẩm</th>
                            <th>Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Đơn Giá</th>
                            <th>Số Lượng</th>
                            <th class="text-primary font-weight-bold">Thành Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $number = 1
                        @endphp

                        @if ( isset($orderDetailList) )
                        @foreach($orderDetailList as $orderDetail)
                        <tr>
                            <td>{{ $number }}</td>
                            <td>{{ $orderDetail->ma_san_pham }}</td>
                            <td>
                                <image src="{{ asset("storage/product/$orderDetail->anh_san_pham") }}" alt="img"
                                    width="80">
                            </td>
                            <td>{{ $orderDetail->ten_san_pham }}</td>
                            <td>{{ $orderDetail->gia }}</td>
                            <td>
                                <input type="number" class="form-control col-md-3" value={{ $orderDetail->so_luong }}>
                            </td>
                            <td class="text-primary font-weight-bold">{{ $orderDetail->thanh_tien }}</td>
                            <td>
                                <a href="{{ url("admin/product/delete/$orderDetail->ma_san_pham ") }}"
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
            </div>
        </div>
        {{-- Tình Trạng Đơn Hàng --}}
        <div class="card">
            <div class="card-body">
                <h5>Tình Trạng</h5>

            </div>
        </div>
    </div>
    {{-- Thông Tin Khách Hàng - Cột bên phải --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Khách Hàng</h5>

            </div>
        </div>
    </div>
    <!-- kết thúc main-container -->
    @endsection
    {{-- Javascript --}}
    @section('script')
    <script>
        // Kiểm tra biến errors từ server gửi về. Nếu có lỗi xuất popup thông báo
        @if(count($errors) > 0)
        Swal.fire({
            title: 'Thất Bại',
            text: 'Vui lòng kiểm tra lại thông tin',
            icon: 'error',
            confirmButtonText: 'OK'
        })
        @endif

        // Kiểm tra biến result
        @if(isset($result))
        @if($result == "fail")
        Swal.fire({
            title: 'Thất Bại',
            text: "{{ $message }}",
            icon: 'error',
            confirmButtonText: 'OK'
        })
        @endif
        @endif

        // CKEditor 5 plugin
        ClassicEditor
            .create(document.querySelector('#product_description'))
            .catch(error => {
                console.error(error);
            });
    </script>
    @endsection