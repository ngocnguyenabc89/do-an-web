<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Thông Tin Cá Nhân</h1>
</div>
<!-- Page Body -->
<div class="card">
    <div class="card-body">
        <!-- Content Row -->
        <form class="conatainer" action="{{ asset('admin/edit-personal-info') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-between">

                {{-- nửa form bên trái --}}
                <div class="col-md-5">
                    <h4 class="mb-5">Thông tin</h4>

                    {{-- mã --}}
                    <div class="form-group row">
                        <label for="user_id" class="col-md-3 col-form-label">Mã</label>
                        <div class="col-md-8">
                            <input type="text" name="user_id" class="form-control" id="user_id" required
                                value="{{ $user->ma_nguoi_dung }}" readonly />
                        </div>
                    </div>

                    <!-- tên -->
                    <div class="form-group row">
                        <label for="user_name" class="col-md-3 col-form-label">Tên</label>
                        <div class="col-sm-8">
                            <input type="text" name="user_name" class="form-control" id="user_name"
                                placeholder="Nhập tên" pattern=".{5,50}" required value="{{ $user->ten_nguoi_dung }}" />
                        </div>
                    </div>

                    <!-- điện thoại -->
                    <div class="form-group row">
                        <label for="user_phone" class="col-md-3 col-form-label">Điện thoại</label>
                        <div class="col-sm-8">
                            <input type="number" name="user_phone" class="form-control" id="user_phone"
                                placeholder="Nhập điện thoại" pattern=".{10,15}" required
                                value="{{ $user->dien_thoai }}" />
                        </div>
                    </div>

                    <!-- ảnh -->
                    <div class="form-group row">
                        <label for="user_image" class="col-md-3 col-form-label">Ảnh đại diện</label>
                        <div class="col-sm-8">
                            <input type="file" name="user_image" class="form-control mb-3 p-1" accept="image/*"
                                id="user_image">
                            <img id="output" src="{{ asset("storage/user/$user->anh_nguoi_dung")}}" width="300"
                                style="border:2px solid #000; border-radius: 5px;" />
                        </div>
                    </div>

                </div>
                {{-- nửa form bên phải --}}
                <div class="col-md-5">
                    <h4 class="mb-5">Tài khoản</h4>

                    <!-- email -->
                    <div class="form-group row">
                        <label for="user_email" class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-8">
                            <input type="email" name="user_email" class="form-control" id="user_email"
                                placeholder="Nhập email" value="{{ $user->email }}" readonly />
                        </div>
                    </div>

                    <!-- đổi mật khẩu?? -->
                    <div class="form-group row">
                        <label for="is_change_password" class="col-md-3 col-form-label">Đổi mật khẩu mới</label>
                        <div class="col-md-1">
                            <input type="checkbox" name="is_change_password" class="form-control"
                                id="is_change_password" />
                        </div>
                    </div>

                    <!-- mật khẩu mới -->
                    <div class="form-group row">
                        <label for="new_password" class="col-md-3 col-form-label">Mật khẩu mới</label>
                        <div class="col-md-8">
                            <input type="password" name="new_password" class="form-control" id="new_password"
                                placeholder="Nhập mật khẩu mới" pattern=".{6,30}"
                                title="Tối thiểu 6 ký tự và tối đa 30 ký tự" required disabled />
                        </div>
                    </div>

                </div>
            </div>

            <!-- submit -->
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mr-3" type="submit">Cập Nhật</button>
                <a href="{{ url('admin/user/list') }}" class="btn btn-secondary">Quay Lại</a>
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
    </div>
    <!-- end card body -->
</div>
<!-- kết thúc main-container -->
@endsection @section('script')
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

    // Hiển thị ảnh upload
    let image_input_DOM = document.querySelector("#user_image");
    image_input_DOM.addEventListener("input", () => {
        let reader = new FileReader();
        let output = document.querySelector("#output");

        reader.onload = (e) => {
            console.log(e.target);
            console.log(output);
            output.src = e.target.result;
        };

        reader.readAsDataURL(image_input_DOM.files[0]);
    });

    // Enable form nhập mật khẩu mới nếu check vào nút đổi bật khẩu
    const checkbox = document.querySelector('#is_change_password')
    checkbox.addEventListener('click', () => {
        let form_password = document.querySelector('#new_password')
        if (checkbox.checked == true) {
            form_password.disabled = false
        } else {
            form_password.disabled = true
        }
    })
</script>
@endsection