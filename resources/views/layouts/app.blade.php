<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  {{-- custom-css --}}
  <link rel="stylesheet" href="{{asset('css/style.css')}}">

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  {{-- SElect 2 --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">


  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">

  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

  {{-- datatables css --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

  {{-- Daterange picker --}}
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  {{-- Viewer.js --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.2/viewer.min.css" integrity="sha512-r+5gXtPk5M2lBWiI+/ITUncUNNO15gvjjVNVadv9qSd3/dsFZdpYuVu4O2yELRwSZcxlsPKOrMaC7Ug3+rbOXw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


  @yield('extra_css')
</head>

<body>

  <div class="page-wrapper chiller-theme">

    <nav id="sidebar" class="sidebar-wrapper">
      <div class="sidebar-content">
        <div class="sidebar-brand">
          <a href="#">Office Hr</a>
          <div id="close-sidebar">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <div class="sidebar-header d-flex">
          <div class="user-pic">
            <img class="img-responsive img-rounded" src="{{auth()->user()->profile_image_path()}}" alt="User picture">
          </div>
          <div class="user-info">
            <span class="user-name pl-1 mb-1">
              <strong>{{auth()->user()->name}}</strong>
            </span>
            <span class="user-role badge badge-pill badge-light mb-1">{{auth()->user()->department ?
              auth()->user()->department->title : 'No department'}}</span>
            <span class="user-status pl-1">
              <i class="fa fa-circle"></i>
              <span>Online</span>
            </span>
          </div>
        </div>
        <!-- sidebar-header  -->

        <div class="sidebar-menu">
          <ul>
            <li class="header-menu">
              <span>General</span>
            </li>
            @can('view_company_setting')
            <li>
              <a href="{{route('company_settings.show',1)}}">
                <i class="fa fa-building"></i>
                <span>Company Setting</span>
              </a>
            </li>
            @endcan
            @can('view_employee')
            <li>
              <a href="{{route('employees.index')}}">
                <i class="fa fa-users"></i>
                <span>Employees</span>
              </a>
            </li>
            @endcan

            @can('view_salary')
            <li>
              <a href="{{route('salaries.index')}}">
                <i class="fa fa-money-bill-alt"></i>
                <span>Salary</span>
              </a>
            </li>
            @endcan

            @can('view_department')
            <li>
              <a href="{{route('departments.index')}}">
                <i class="fa fa-briefcase"></i>
                <span>Department</span>
              </a>
            </li>
            @endcan

            @can('view_role')
            <li>
              <a href="{{route('roles.index')}}">
                <i class="fa fa-door-closed"></i>
                <span>Role</span>
              </a>
            </li>
            @endcan

            @can('view_permission')
            <li>
              <a href="{{route('permissions.index')}}">
                <i class="fa fa-lock-open"></i>
                <span>Permission</span>
              </a>
            </li>
            @endcan

            @can('view_project')
            <li>
              <a href="{{route('projects.index')}}">
                <i class="fa fa-toolbox"></i>
                <span>Project</span>
              </a>
            </li>
            @endcan

            @can('view_attendance')
            <li>
              <a href="{{route('attendances.index')}}">
                <i class="fa fa-calendar-check"></i>
                <span>Attendance</span>
              </a>
            </li>
            @endcan

            @can('view_attendance_overview')
            <li>
              <a href="{{route('attendances.overview')}}">
                <i class="fa fa-calendar-check"></i>
                <span>Attend_overview</span>
              </a>
            </li>
            @endcan

            @can('view_payroll')
            <li>
              <a href="{{route('payroll.overview')}}">
                <i class="fa fa-poll-h"></i>
                <span>Payroll</span>
              </a>
            </li>
            @endcan

          </ul>
        </div>
        <!-- sidebar-menu  -->
      </div>
    </nav>
    <!-- sidebar-wrapper  -->

  </div>
  <!-- page-wrapper -->


  <div class="app-menu">
    <div class="d-flex justify-content-center">
      <div class="col-md-10">
        <div class="d-flex justify-content-between">
          @if(request()->is('/'))
          <a href="#" id="show-sidebar"><i class="fas fa-bars"></i></a>
          @else
          <a href="#" id='back-btn'><i class="fas fa-chevron-circle-left"></i></a>
          @endif
          @yield('title')
          <a href=""></a>
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-center">
    <div class="col-md-10 ">
      <div class="py-4 content">
        @yield('content')
      </div>
    </div>
  </div>

  <div class="bottom">
    <div class="d-flex justify-content-center">
      <div class="col-md-10">
        <div class="d-flex justify-content-between">
          <a href="{{route('home')}}"><i class="fas fa-home"></i>
            <p class="mb-0">Home</p>
          </a>
          <a href="{{route('attendance.scan')}}"><i class="fas fa-user-check"></i>
            <p class="mb-0">Attend</p>
          </a>
          <a href="{{route('my-projects.index')}}"><i class="fas fa-project-diagram"></i>
            <p class="mb-0">Project</p>
          </a>
          <a href="{{route('profile')}}"><i class="fas fa-user"></i>
            <p class="mb-0">Profile</p>
          </a>
        </div>
      </div>
    </div>
  </div>




  <!-- JQuery -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js">
  </script>

  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

  {{-- DataTables js --}}
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.jsdelivr.net/g/mark.js(jquery.mark.min.js)"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.js"></script>

  {{-- Date range picker --}}
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  {{------- Sweet Alert ---------------}}
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  {{------- Sweet Alert 2 ---------------}}
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Select 2 --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  {{------LaraPass authenttication --}}
  <script src="{{ asset('vendor/larapass/js/larapass.js') }}"></script>

  {{-- Viewer.js --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.2/viewer.min.js" integrity="sha512-lzNiA4Ry7CjL8ewMGFZ5XD4wIVaUhvV3Ct9BeFmWmyq6MFc42AdOCUiuUtQgkrVVELHA1kT7xfSLoihwssusQw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  {{-- js validation --}}
  <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

  <script>
    //sweet alert 2
        const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })  



        $(function ($) {
          //ajax csrf token
          let token = document.head.querySelector('meta[name="csrf-token"]');
                
                if(token){
                    $.ajaxSetup({
                        headers : {
                            'X-CSRF-Token' : token.content,
                        }
                    })
                }else{
                    console.error('error');
                }


                //back-btn 
                $('#back-btn').on('click',function(e){
                    e.preventDefault();
                    window.history.go(-1);
                    return false;
                })

                //Select 2
                $('.select-role').select2({
                  theme: 'bootstrap4',
                });


                //side bar
                $(".sidebar-dropdown > a").click(function() {
                    $(".sidebar-submenu").slideUp(200);
                    if ($(this).parent().hasClass("active")) {
                        $(".sidebar-dropdown").removeClass("active");
                        $(this).parent().removeClass("active");
                    } else {
                        $(".sidebar-dropdown").removeClass("active");
                        $(this).next(".sidebar-submenu").slideDown(200);
                        $(this).parent().addClass("active");
                        }
                    });

                    $("#close-sidebar").click(function(e) {
                        e.preventDefault();
                        $(".page-wrapper").removeClass("toggled");
                    });

                    $("#show-sidebar").click(function(e) {
                        e.preventDefault();
                        $(".page-wrapper").addClass("toggled");
                    });

                    @if(request()->is('/'))
                    document.addEventListener('click',function(event){
                      if(document.getElementById('show-sidebar').contains(event.target)){
                        $(".page-wrapper").addClass("toggled");
                      }else if(!document.getElementById('show-sidebar').contains(event.target)){
                        $(".page-wrapper").removeClass("toggled");
                      }
                    })
                    @endif


                    //Datatables
                    $.extend(true, $.fn.dataTable.defaults, {
                                        processing: true,
                                        serverSide: true,
                                        responsive: true,
                                        mark: true,
                                        "columnDefs": [
                                    {
                                        "targets": "hidden",
                                        "visible": false,
                                    },
                                    {
                                        "targets": [ 0 ],
                                        "class": "control",
                                    },
                                    {
                                        "targets": 'no-sort',
                                        "orderable": false,
                                    },
                                    {
                                        "targets": 'no-search',
                                        "searchable": false,
                                    }
                                ],
                        "language": {
                            "paginate" :{
                                "next": "<i class='fas fa-arrow-alt-circle-right'></i>",
                                "previous": "<i class='fas fa-arrow-alt-circle-left'></i>",
                            },
                            "processing":     "<img src='/image/loading.gif' width='60px' /><p class='my-2'>Loading ...</p> "
                        }
                    });              
            
          });

  </script>
  @yield('scripts')


</body>

</html>