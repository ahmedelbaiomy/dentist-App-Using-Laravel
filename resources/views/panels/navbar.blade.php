<!-- BEGIN: Header-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav">
            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{ route('admin.doctor') }}" data-toggle="tooltip" data-placement="top" title="doctors"><i class="ficon" data-feather="user-plus"></i></a></li>
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style" href="{{ route('admin.schedules') }}" data-toggle="tooltip" data-placement="top" title="Doctor Schedule Timings"><i class="ficon" data-feather="clock"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="sun"></i></a></li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">{{ Auth::user()->name }}</span><span class="user-status">{{ Auth::user()->username }}</span></div><span class="avatar"><img
                            class="round" src="{{ asset('new-assets/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar"
                            height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    
                    <a class="dropdown-item" href="{{ route('account_setting') }}"><i
                            class="mr-50" data-feather="settings"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                            
                            <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        href="{{ route('logout') }}"><i class="mr-50" data-feather="power"></i> Logout</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- END: Header-->