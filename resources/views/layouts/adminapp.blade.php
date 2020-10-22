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
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">

  <link rel="stylesheet" href="{{asset('assets/vendor/dropzone/dist/min/dropzone.min.css')}}">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/argon.css?v=1.1.0')}}" type="text/css">

  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}" type="text/css">
  @yield('pagewise_css')
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
  <!-- <script src="{{asset('assets/vendor/dropzone-1/dropzone.js')}}"></script> -->
  
  <script src="{{asset('assets/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

  <!-- Optional JS -->
  <script src="{{asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('assets/vendor/datatables.net-select/js/dataTables.select.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/dist/Chart.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/dist/Chart.extension.js')}}"></script>
  <script src="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
  <script src="{{asset('assets/vendor/select2/dist/js/select2.min.js')}}"></script>
  <script src="{{asset('assets/vendor/quill/dist/quill.min.js')}}"></script>
  <script src="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
  <!-- Argon JS -->
  <script src="{{asset('assets/js/argon.js?v=1.1.0')}}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{asset('assets/js/demo.min.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
    // show the alert
      setTimeout(function() {
        $(".alert-dismissible").fadeTo(2000, 500).slideUp(800, function(){
          $(".alert-dismissible").alert('close');
        });
      }, 2000);
    });
  </script>
  @yield('pagewise_js')
</body>

</html>