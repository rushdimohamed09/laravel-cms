
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}

    <title>CMS</title>
    <!-- plugins start -->
    <link rel="stylesheet" href="{{ url('public/assets/auth/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/auth/vendors/css/vendor.bundle.base.css') }}">
    <!-- plugins end -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ url('public/assets/auth/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ url('public/assets/auth/images/favicon.ico') }}" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="{{ url('/')}}">Home</a>
            <a class="navbar-brand brand-logo-mini" href="{{url('/')}}">Home</a>
          {{-- <a class="navbar-brand brand-logo" href="/">
            <img src="{{ url('public/assets/auth/images/logo.svg') }}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="/">
            <img src="{{ url('public/assets/auth/images/logo-mini.svg') }}" alt="logo" />
          </a> --}}
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          {{-- <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
              </div>
            </form>
          </div> --}}
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="nav-profile-img">
                    {{-- <img src="{{ url('public/assets/auth/images/faces/face1.jpg') }}" alt="image">
                    <span class="availability-status online"></span> --}}
                  </div>
                  <div class="nav-profile-text">
                    <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                  </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                  {{-- <a class="dropdown-item" href="/admin/profile">
                    <i class="mdi mdi-account me-2 text-success"></i> Profile </a>
                  <div class="dropdown-divider"></div> --}}
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </div>
              </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            {{-- <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="{{ url('public/assets/auth/images/faces/face1.jpg') }}" alt="profile">
                  <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
                  <span class="text-secondary text-small">{{ ucfirst(Auth::user()->role->name) }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li> --}}
            {{-- {{ dd(Auth::user()->permissions()) }} --}}
            @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['dashboard:view']))
            <li class="nav-item">
                <a class="nav-link" href="{{url('/admin/dashboard')}}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            @endif
            @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['articles:view', 'articles:add']))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#articles" aria-expanded="false" aria-controls="articles">
                    <span class="menu-title">Articles</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-table-large menu-icon"></i>
                </a>
                <div class="collapse" id="articles">
                    <ul class="nav flex-column sub-menu">
                        @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['articles:view']))
                        <li class="nav-item"> <a class="nav-link" href="{{url('/admin/articles')}}">View Articles</a></li>
                        @endif
                        @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['articles:add']))
                        <li class="nav-item"> <a class="nav-link" href="{{url('/admin/add-article')}}">Add Article</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['manager:view', 'manager:add', 'employee:view', 'employee:add']))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#users" aria-expanded="false" aria-controls="users">
                    <span class="menu-title">User Management</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account menu-icon"></i>
                </a>
                <div class="collapse" id="users">
                    <ul class="nav flex-column sub-menu">
                        @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['manager:view', 'employee:view']))
                        <li class="nav-item"> <a class="nav-link" href="{{url('/admin/users')}}">View Users</a></li>
                        @endif
                        @if(\App\Helpers\PermissionsHelper::hasPermissionOrIsAdmin(['manager:add', 'employee:add']))
                        <li class="nav-item"> <a class="nav-link" href="{{url('/admin/add-user')}}">Add User</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
          </ul>
        </nav>

        <!-- partial -->
        <div class="main-panel">

          <div class="content">
            @yield('content')
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
              <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © company 2023</span>
              <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Designed and Developed by <a href="https://www.alternative.agency" target="_blank"> Alternative Agency </a></span>
            </div>
          </footer>
          <!-- partial -->
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- sidebar start -->
    <script src="{{ url('public/assets/auth/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ url('public/assets/auth/js/off-canvas.js') }}" ></script>
    <script src="{{ url('public/assets/auth/js/hoverable-collapse.js') }}" ></script>
    <script src="{{ url('public/assets/auth/js/misc.js') }}" ></script>
    <!-- sidebar end -->

    <script>
      $(document).ready(function() {

        var pressedOk = false;
        $('#myForm').submit(function(event) {
          event.preventDefault();

          let passwordChk = false;
          var isPasswordNil = checkForPasswordNil();

          passwordChk = !isPasswordNil;

          if (passwordChk) {
            if(!checkForCodeView()) {
              $('#myForm').unbind('submit').submit();
            }
          } else {
            if(!pressedOk && !checkForCodeView()) {
              handleFormSubmission();
            }
          }
        });

        function handleFormSubmission() {
          if (formID !== null) {
            $(formID).unbind('submit').submit();
          } else {
            if (pressedOk && !checkForCodeView()) {
              $('#myForm').unbind('submit').submit();
            }
          }
        }
      });
    </script>
    <!-- Custom js for this page -->

    <style>
      body {
        display: flex;
        flex-direction: column;
      }

      .content {
        flex: 1;
      }

      .note-editable {
        background-color: #ffffff
      }
      .input-height {
        height: 30px;
      }
      .note-modal-footer {
        height: 60px !important;
      }
      button.remove-section-btn.initial {
        display: none;
      }

      .truncate-text {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
      }

      .no-access {
        min-height: 560px;
        display: flex;
        min-height: 560px;
        flex-direction: column;
        justify-content: center;
      }
    </style>
  </body>
</html>
