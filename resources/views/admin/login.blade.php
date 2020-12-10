<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>3Gs House - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin-assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin-assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-custom.css') }}" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                <p style="
                                font-size: 2rem;
                                background: -webkit-linear-gradient(#1cc88a, #224abe);
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                font-weight: bold;
                                ">3Gs HOUSE</[p]>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Đăng Nhập Để Tiếp Tục</h1>
                                    </div>
                                    <form class="user" method="POST" action="{{ url('admin/login')}}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" name="user_email" class="form-control form-control-user"
                                                id="user_email" aria-describedby="emailHelp" required
                                                placeholder="Nhập Email...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="user_password"
                                                class="form-control form-control-user" id="user_password" required
                                                placeholder="Nhập Mật Khẩu...">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Đăng Nhập
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin-assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('admin-assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin-assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin-assets/js/sb-admin-2.min.js')}}"></script>

    <!-- Sweet Alert 2 plugin -->
    <script src="{{ asset('admin-assets/js/sweetalert2.js')}}"></script>

    {{-- Custom JS --}}
    <script>
        // Thông báo đăng xuất
        @if (Session::get('logout_message') != null)
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get('logout_message') }}',
            text: 'Đăng nhập để tiếp tục',
            showConfirmButton: false,
            timer: 1500
        }).then((result)=>{
            {{ Session::put('logout_message', null)}}
        })
        @endif

        // Thông báo đăng nhập
        @if(isset($result))
        @if($result == "success")
        Swal.fire({
            icon: 'success',
            title: '{{ $title }}',
            text: '{{ $message }}',
            showConfirmButton: false,
            timer: 1500
        }).then((result) => {

            @if ($type=='login') 
                location.assign("{{ url('admin/dashboard') }}")
            @endif
        })
        @else
        Swal.fire({
            icon: 'error',
            title: 'Đăng Nhập Thất Bại',
            text: 'Vui lòng kiểm tra lại thông tin',
        })
        @endif
        @endif

    </script>

</body>

</html>