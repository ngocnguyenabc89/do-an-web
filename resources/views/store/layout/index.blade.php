<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Cake Template">
    <meta name="keywords" content="Cake, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cake | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('store-assets/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/flaticon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/barfiller.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('store-assets/css/style.css') }}" type="text/css">

</head>

<body>
    <!-- Page Preloder -->
    {{-- <div id="preloder">
        <div class="loader"></div>
    </div> --}}

    <!-- Offcanvas Menu Begin -->
    @include('store.layout.offcanvas-menu')
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('store.layout.topbar')
    <!-- Header Section End -->

    <!-- Main Content Begin -->
    <div id="main-content">
        @yield('main-container')
    </div>
    <!-- Main Content End -->

    <!-- Footer Section Begin -->
    @include('store.layout.footer')
    <!-- Footer Section End -->

    <!-- Search Begin -->
    {{-- <div class="search-model">
        @include('store.layout.search')
    </div> --}}
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="{{ asset('store-assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('store-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('store-assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('store-assets/js/jquery.barfiller.js') }}"></script>
    <script src="{{ asset('store-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('store-assets/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('store-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('store-assets/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('store-assets/js/main.js') }}"></script>
    <!-- Sweet Alert 2 plugin -->
    <script src="{{ asset('admin-assets/js/sweetalert2.js')}}"></script>
    <!-- JS tự viết -->
    @yield('script')

    <script>
        // Kiểm tra kết quả xử lý
				@if(Session::has('success'))
        Swal.fire({
            title: 'Thành Công',
            text: "{{ Session::get('success') }}",
            icon: 'success',
            showConfirmButton: false,
            timer: 1300
        })
        @elseif(Session::has('fail'))
        Swal.fire({
            title: 'Thất Bại',
            text: "{{ Session::get('fail') }}",
            icon: 'error',
            showConfirmButton: true,
        })
        @endif
    </script>
</body>

</html>