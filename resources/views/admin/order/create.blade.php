<!-- Khai báo sử dụng layout admin -->
@extends('admin.layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Tạo Mới</h1>
    <h5><a class="text-primary mb-5" href="{{url('admin/product/list')}}">Sản phẩm</a> / Tạo Mới</h5>
</div>
<!-- Page Body -->
<div class="card">
    <div class="card-body">
        <!-- Content Row -->
        <form class="conatainer" action="{{ asset('admin/product/create') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-between">

                {{-- nửa form bên trái --}}
                <div class="col-md-5">
                    <h4 class="mb-5">Thông tin</h4>

                    <!-- tên -->
                    <div class="form-group row">
                        <label for="product_name" class="col-md-3 col-form-label">Tên</label>
                        <div class="col-sm-8">
                            <input type="text" name="product_name" class="form-control font-weight-bold"
                                id="product_name" placeholder="Nhập tên" pattern=".{5,40}"
                                title="Tối thiểu 5 ký tự và tối đa 40 ký tự" required />
                        </div>
                    </div>

                    <!-- giá -->
                    <div class="form-group row">
                        <label for="product_price" class="col-md-3 col-form-label">Giá bán</label>
                        <div class="col-sm-8">
                            <input type="number" name="product_price"
                                class="form-control text-danger text-right font-weight-bold" id="product_price"
                                placeholder="Nhập giá bán" min="1000" max="10000000000" step="1000" required />
                        </div>
                    </div>

                    <!-- số lượng -->
                    <div class="form-group row">
                        <label for="product_quantity" class="col-md-3 col-form-label">Số lượng</label>
                        <div class="col-sm-8">
                            <input type="number" name="product_quantity" class="form-control text-right"
                                id="product_quantity" placeholder="Nhập số lượng" min="0" max="100000000" step="1"
                                required value="0" />
                        </div>
                    </div>

                    <!-- tình trạng -->
                    <div class="form-group row">
                        <label for="product_status" class="col-md-3 col-form-label">Tình trạng</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="product_status" id="product_status">
                                <option value="1" class="font-weight-bold">Mở bán</option>
                                <option value="0" class="font-weight-bold">Tạm ngưng</option>
                            </select>
                        </div>
                    </div>

                    <!-- phân loại -->
                    <div class="form-group row">
                        <label for="product_type" class="col-md-3 col-form-label">Phân loại</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="product_type" id="product_type">
                                <option value="0" class="font-weight-bold">Sản phẩm thường</option>
                                <option value="1" class="font-weight-bold">Sản phẩm nổi bật</option>
                            </select>
                        </div>
                    </div>

                    <!-- danh mục -->
                    <div class="form-group row">
                        <label for="product_category" class="col-md-3 col-form-label">Danh mục</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="product_category" id="product_category">
                                @foreach ($categoryList as $category)
                                <option value="{{ $category->ma_danh_muc }}" class="font-weight-bold">
                                    {{ $category->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- ảnh -->
                    <div class="form-group row">
                        <label for="product_image" class="col-md-3 col-form-label">Ảnh đại diện</label>
                        <div class="col-sm-8">
                            <input type="file" name="product_image" class="form-control mb-3 p-1" accept="image/*"
                                id="product_image" required>
                            <img id="output" src="{{ asset('images/default.png')}}" width="300"
                                style="border:2px solid #000; border-radius: 5px;" />
                        </div>
                    </div>

                </div>
                {{-- nửa form bên phải --}}
                <div class="col-md-7">
                    <h4 class="mb-5">Mô Tả Sản Phẩm</h4>

                    <!-- mô tả -->
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="product_description" id="product_description"
                                rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- submit -->
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mr-3" type="submit">Lưu</button>
                <a href="{{ url('admin/product/list') }}" class="btn btn-secondary">Hủy</a>
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

        // Hiển thị ảnh upload
        let image_input_DOM = document.querySelector("#product_image");
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

        // CKEditor 5 plugin
        ClassicEditor
            .create( document.querySelector( '#product_description' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    @endsection