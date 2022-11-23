<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="{{ asset('') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IoT Farm</title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/assets_admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/assets_admin/css/sb-admin-2.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/js/jquery-confirm/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap_backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/jquery-ui-1.12.1/jquery-ui.css') }}">
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/assets_admin/vendor/jquery/jquery.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> -->
    <script src="{{ asset('assets/assets_admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/assets_admin/js/sb-admin-2.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/assets_admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <!-- {{-- <script src="{{asset('assets_admin/js/sb-admin-2.js')}}"></script> --}} -->
    <!-- <script src="{{ asset('assets/js/jquery-ui-1.12.1/jquery-ui.js') }}"></script> -->
    <script src="{{ asset('assets/bootstrap_backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.bootgrid-1.3.1/jquery.bootgrid.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.tablednd.js') }}"></script>
    <script src="{{ asset('assets/js/util.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/date_time_picker/css/bootstrap-datetimepicker.min.css') }}">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script> -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script> -->
    <script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/date_time_picker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js')}}"></script>
    <!-- Page level plugins -->
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('admin.admin_sidebar')
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('admin.admin_header')
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            @include('admin.admin_footer')
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác Nhận</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Bạn muốn thoát khỏi phiên đăng nhập này?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Hủy</button>
                    <a class="btn btn-primary" href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Thoát</a>
                </div>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <div class="modal-loading"></div>
</body>
</html>
