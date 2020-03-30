    <div class="wrapper">
        <div class="sidebar" data-color="blue" data-image="{{ asset('assets/img/sidebar-5.jpg')}}">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="{{route('dashboard')}}" class="simple-text">
                        Meal Allowance
                    </a>
                </div>
                <ul class="nav">
                    <li class="nav-item {{ isset($menu_name) ? ($menu_name == 'dashboard') ? 'active' : '' : ''}}">
                        <a class="nav-link" href="{{route('dashboard')}}">
                            <i class="fas fa-chalkboard"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#pageSubmenu" role="button" data-toggle="collapse" aria-expanded="{{ isset($menu_name) ? ($menu_name == 'employee') ? 'true' : 'false' : 'false' }}" class="nav-link dropdown-toggle" aria-controls="pageSubmenu"> 
                            <i class="fas fa-user-tie"></i><p>Employee</p>
                        </a>
                        <ul class="{{ isset($menu_name) ? ($menu_name == 'employee') ? 'show' : '' : '' }} collapse multi-collapse list-unstyled" id="pageSubmenu">
                            <li class="nav-item {{ isset($emp_list) ? ($emp_list == 'active_emp') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('emplist')}}">Active Employee</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('inactivelist')}}">Inactive Employee</a>
                            </li>
                            <li class="nav-item {{ isset($emp_list) ? ($emp_list == 'res_emp') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('resigned')}}">Resigned Employee</a>
                            </li>
                            <li class="nav-item {{ isset($emp_list) ? ($emp_list == 'mass_emp') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('mass_view')}}">Mass Upload</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a role="button" href="#pageSubmenu" data-toggle="collapse" aria-expanded="{{ isset($menu_name) ? ($menu_name == 'meal_gen') ? 'true' : 'false' : 'false' }}" class="nav-link dropdown-toggle" data-target="#pageSubmenu2" aria-controls="pageSubmenu2"> 
                            <i class="fas fa-hamburger"></i><p>Meal Allowance</p>
                        </a>
                        <ul class="{{ isset($menu_name) ? ($menu_name == 'meal_gen') ? 'show' : '' : '' }} collapse multi-collapse list-unstyled" id="pageSubmenu2">
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'regall') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('RegView')}}">Regular Allowance</a>
                            </li>
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'otall') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('OTView')}}">OT Allowance</a>
                            </li>
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'genlist') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('GenList')}}">List of generated allowance</a>
                            </li>
                            @if(Auth::user()->role != 4)
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'penlist') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('PenList')}}">Pending Allowance <span class="notification" id="notif"></span></a>
                            </li>
                            @else
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item {{ isset($menu_name) ? ($menu_name == 'cost_center') ? 'active' : '' : '' }}">
                        <a class="nav-link" href="{{route('costcenter')}}">
                            <i class="fas fa-dollar-sign"></i>
                            <p>Cost Center</p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($menu_name) ? ($menu_name == 'canteen') ? 'active' : '' : '' }}">
                        <a class="nav-link" href="{{route('canteen')}}">
                            <i class="fas fa-store"></i>
                            <p>Canteen Setting</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="button" href="#pageSubmenu" data-toggle="collapse" aria-expanded="{{ isset($menu_name) ? ($menu_name == 'rep_gen') ? 'true' : 'false' : 'false' }}" class="nav-link dropdown-toggle" data-target="#pageSubmenu3" aria-controls="pageSubmenu3"> 
                            <i class="fas fa-copy"></i>
                            <p>Reports</p>
                        </a>
                        <ul class="{{ isset($menu_name) ? ($menu_name == 'rep_gen') ? 'show' : '' : '' }} collapse multi-collapse list-unstyled" id="pageSubmenu3">
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'cost_center_rep') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('costcenter_rep')}}">Cost Center Report</a>
                            </li>
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'history_rep') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('historical_rep')}}">Historical Report</a>
                            </li>
                            @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                            <li class="nav-item {{ isset($meal_list) ? ($meal_list == 'audit') ? 'active' : '' : '' }}">
                                <a class="nav-link" href="{{route('audit_rep')}}">Audit Trail</a>
                            </li>
                            @else
                    
                            @endif
                        </ul>
                    </li>
                    @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                    <li class="nav-item {{ isset($menu_name) ? ($menu_name == 'user') ? 'active' : '' : '' }}">
                        <a class="nav-link" href="{{route('userlist')}}">
                            <i class="fas fa-user-cog"></i>
                            <p>User Maintenance</p>
                        </a>
                    </li>
                    @else
                    
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target=".change_pass">
                            <i class="fab fa-keycdn"></i>
                            <p>Change Password</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ asset('/manual/user_manual.pdf') }}" download>
                            <i class="fas fa-file-download"></i>
                            <p>User Manual</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{isset($routes) ? $routes : route('dashboard')}}"> {{isset($title) ? $title : ''}} </a>
                    <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                        <span class="navbar-toggler-bar burger-lines"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="no-icon">{{ Auth::user()->name }}</span>
                                </a>
                                <a class="nav-link" href="#">
                                    <span class="no-icon">{{ role(Auth::user()->role) }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <p class="copyright text-center">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            <a href="#">IT System Developer</a>, made with love for a better web
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>
