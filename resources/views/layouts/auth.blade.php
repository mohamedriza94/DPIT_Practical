<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/assets/images/favicon.png') }}">
    <title>{{ $title }}</title>
    <link href="{{ asset('admin/assets/dist/css/pages/login-register-lock.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/dist/css/style.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">DPIT</p>
        </div>
    </div>
    
    <section id="wrapper" class="login-register login-sidebar" style="background-image:url(https://wallpapercave.com/wp/wp2019265.jpg);">
        
        
        
        
        
        @yield('content')
        @yield('script')
        
        
        
        
        
    </section>
    <script src="{{ asset('admin/assets/node_modules/jquery/dist/jquery.min.js') }} "></script>
    <script src="{{ asset('admin/assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
        
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        
        $('#back').on("click", function() {
            $("#loginform").slideDown();
            $("#recoverform").fadeOut();
        });
    </script>
    
</body>

</html>