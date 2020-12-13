<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Tạo Mới</h1>
    <h5><a class="text-primary mb-5" href="{{url('admin/category/list')}}">Danh Mục</a> / Tạo Mới</h5>
</div>
<!-- Page Body -->
<div class="card">
    <div class="card-body">

        <!-- Content Row -->
        <form class="conatainer" action="{{ url('admin/category/create') }}" method="POST">
            @csrf
            <div class="row d-flex justify-content-between">
                <div class="col-md-5">

                    <!-- tên -->
                    <div class="form-group row">
                        <label for="category_name" class="col-md-3 col-form-label">Tên</label>
                        <div class="col-sm-8">
                            <input type="text" name="category_name" class="form-control" id="category_name"
                                placeholder="Nhập tên" pattern=".{3,40}" title="Tối thiểu 3 ký tự và tối đa 40 ký tự"
                                required />
                        </div>
                    </div>

                    <!-- mô tả -->
                    <div class="form-group row">
                        <label for="category_description" class="col-md-3 col-form-label">Mô tả</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="category_description" id="category_description"
                                rows="5"></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <!-- submit -->
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mr-3" type="submit">Lưu</button>
                <a href="{{ url('admin/category/list') }}" class="btn btn-secondary">Hủy</a>
            </div>

        </form>

        <!-- errors -->
        <div class="row">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                {{$error}}<br>
                @endforeach
            </div>
            @endif
        </div>
        <!-- end card body -->
    </div>
    <!-- kết thúc main-container -->
    @endsection

    <!-- Javascript -->
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

    </script>
    @endsection