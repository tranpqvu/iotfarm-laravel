   <!-- Sidebar -->
   <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

       <!-- Sidebar - Brand -->
       <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/admin') }}">
           <!--div class="sidebar-brand-icon rotate-n-15">
               <i class="fas fa-laugh-wink"></i>
           </div-->
           <div class="sidebar-brand-text mx-3">Happy Farm</div>
       </a>

       <!-- Divider -->
       <hr class="sidebar-divider my-0">

       <!-- Nav Item - Dashboard -->
       <li class="nav-item active">
           <a class="nav-link" href="{{ url('/admin') }}">
               <i class="fas fa-fw fa-tachometer-alt"></i>
               <span>Agriculture IOT System</span></a>
       </li>

       <!-- Divider -->
       <hr class="sidebar-divider">

       <!-- Heading -->
       <!--div class="sidebar-heading">
           Agriculture IOT System
       </div>

       <!-- Nav Item - Pages Collapse Menu -->
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Điều Khiển Nông Trại</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Giám Sát Dữ Liệu</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Truy Xuất Dữ Liệu</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Cài Đặt</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Hướng Dẫn Sử Dụng</span></a>
       </li>
       
       <!-- Divider -->
       <hr class="sidebar-divider">
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/farm') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Nông Trại</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/area') }}">
               <i class="fa fa-map" aria-hidden="true"></i>
               <span>Khu Vực</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/device') }}">
               <i class="fa fa-map-signs" aria-hidden="true"></i>
               <span>Thiết Bị</span></a>
       </li>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/#') }}">
               <i class="fa fa-server" aria-hidden="true"></i>
               <span>Điều Khiển</span></a>
       </li>
       <!--li class="nav-item">
           <a class="nav-link" href="{{ url('admin/cau-hinh-bai-kiem-tra') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Cấu Hình Kiểm Tra</span></a>
       </li>

       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/ql-bai-kiem-tra') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Bài Kiểm Tra</span></a>
       </li>
       {{-- <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
               aria-expanded="true" aria-controls="collapseUtilities">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Bài Kiểm Tra</span>
           </a>
           <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
               data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Môn Học:</h6>

                   @isset($subjects)
                       @foreach ($subjects as $item)
                          <a class="collapse-item" href="{{ url('admin/ql-bai-kiem-tra/') }}">{{ $item->name }}</a>
                       @endforeach
                   @endisset
               </div>
           </div>
       </li> --}}

       {{-- <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSubjects"
               aria-expanded="true" aria-controls="collapseUtilities">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Lớp Học</span>
           </a>
           <div id="collapseSubjects" class="collapse" aria-labelledby="headingUtilities"
               data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   @isset($subjects)
                       @foreach ($subjects as $item)
                           <a class="collapse-item" href="utilities-border.html">{{ $item->name }}</a>
                       @endforeach
                   @endisset
                  <h6 class="collapse-header">2020-2021</h6>
                   <a class="collapse-item" href="utilities-color.html">Điện Dân Dụng</a>
                   <a class="collapse-item" href="utilities-border.html">Tin Học</a>
                   <a class="collapse-item" href="utilities-animation.html">May</a>
                   <a class="collapse-item" href="utilities-other.html">Làm Vườn</a> 
               </div>
           </div>
       </li> --}}
       <--?php if (auth()->user() != null && auth()->user()->level == 0) { ?>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/ql-phan-mon') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Phân Môn Học</span>
           </a>
       </li>
       <--?php } ?>

       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/ql-lop-hoc') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Lớp Học</span>
           </a>
       </li>

       <--?php if (auth()->user() != null && auth()->user()->level == 0) { ?>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/ql-mon-hoc') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Môn Học</span>
           </a>
       </li>
       <--?php } ?>

       <--?php if (auth()->user() != null && auth()->user()->level == 0) { ?>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/ql-nam-hoc') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Năm Học</span></a>
       </li>
       <--?php } ?>

       <--?php if (auth()->user() != null && auth()->user()->level == 0) { ?>
       <li class="nav-item">
           <a class="nav-link" href="{{ url('admin/ql-giao-vien') }}">
               <i class="fas fa-fw fa-tasks"></i>
               <span>Giáo Viên</span>
           </a>
       </li>
       <--?php } ?-->




       <!-- <li class="nav-item">
       <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
         <i class="fas fa-fw fa-cog"></i>
         <span>Components</span>
       </a>
       <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
         <div class="bg-white py-2 collapse-inner rounded">
           <h6 class="collapse-header">Custom Components:</h6>
           <a class="collapse-item" href="buttons.html">Buttons</a>
           <a class="collapse-item" href="cards.html">Cards</a>
         </div>
       </div>
     </li-->

       <!-- Nav Item - Utilities Collapse Menu -->
       <!-- <li class="nav-item">
       <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
         <i class="fas fa-fw fa-wrench"></i>
         <span>Utilities</span>
       </a>
       <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
         <div class="bg-white py-2 collapse-inner rounded">
           <h6 class="collapse-header">Custom Utilities:</h6>
           <a class="collapse-item" href="utilities-color.html">Colors</a>
           <a class="collapse-item" href="utilities-border.html">Borders</a>
           <a class="collapse-item" href="utilities-animation.html">Animations</a>
           <a class="collapse-item" href="utilities-other.html">Other</a>
         </div>
       </div>
     </li> -->

       <!-- Divider -->
       {{-- <hr class="sidebar-divider"> --}}

       <!-- Heading -->
      {{--  <div class="sidebar-heading">
           Addons
       </div>

       <!-- Nav Item - Pages Collapse Menu -->
       <li class="nav-item">
           <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
               aria-expanded="true" aria-controls="collapsePages">
               <i class="fas fa-fw fa-folder"></i>
               <span>Pages</span>
           </a>
           <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
               <div class="bg-white py-2 collapse-inner rounded">
                   <h6 class="collapse-header">Login Screens:</h6>
                   <a class="collapse-item" href="login.html">Login</a>
                   <a class="collapse-item" href="register.html">Register</a>
                   <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                   <div class="collapse-divider"></div>
                   <h6 class="collapse-header">Other Pages:</h6>
                   <a class="collapse-item" href="404.html">404 Page</a>
                   <a class="collapse-item" href="blank.html">Blank Page</a>
               </div>
           </div>
       </li>

       <!-- Nav Item - Charts -->
       <li class="nav-item">
           <a class="nav-link" href="charts.html">
               <i class="fas fa-fw fa-chart-area"></i>
               <span>Charts</span></a>
       </li>

       <!-- Nav Item - Tables -->
       <li class="nav-item">
           <a class="nav-link" href="tables.html">
               <i class="fas fa-fw fa-table"></i>
               <span>Tables</span></a>
       </li> --}}

       <!-- Divider -->
       <hr class="sidebar-divider d-none d-md-block">

       <!-- Sidebar Toggler (Sidebar) -->
       <div class="text-center d-none d-md-inline">
           <button class="rounded-circle border-0" id="sidebarToggle"></button>
       </div>
   </ul>
   <!-- End of Sidebar -->
