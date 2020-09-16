<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ config('app.name', 'Argon Dashboard') }}</title>
  <!-- Favicon -->
  <link rel="icon" href="{{asset('assets/img/brand/favicon.png')}}" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="{{asset('assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
  <link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/argon.css?v=1.1.0')}}" type="text/css">
</head>

<body>
     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
  @include('layouts.admin.sidebar')
  <!-- Main content -->
  <div class="main-content" id="panel">
    @include('layouts.admin.header')
    <!-- Header -->
    <!-- Header -->
    @yield('content')

  </div>
  @include('layouts.admin.footer')

  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/js-cookie/js.cookie.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
  <script src="{{asset('assets/vendor/dropzone/dist/min/dropzone.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
  <!-- Argon JS -->
  <script src="{{asset('assets/js/argon.js?v=1.1.0')}}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{asset('assets/js/demo.min.js')}}"></script>
</body>

</html>