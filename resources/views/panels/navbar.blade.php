<!-- BEGIN: Header-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav">
                @if(Auth::user())
                @if(Auth::user()->user_type == "admin")
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{ route('admin.doctor') }}"
                        data-toggle="tooltip" data-placement="top" title="doctors"><i class="ficon"
                            data-feather="user-plus"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"
                        href="{{ route('admin.schedules') }}" data-toggle="tooltip" data-placement="top"
                        title="Doctor Schedule Timings"><i class="ficon" data-feather="clock"></i></a></li>
                @elseif(Auth::user()->user_type == "doctor")
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{ route('doctor.home') }}"
                        data-toggle="tooltip" data-placement="top" title="Dashboard"><i class="ficon"
                            data-feather="home"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"
                        href="{{ route('doctor.appointment') }}" data-toggle="tooltip" data-placement="top"
                        title="Appointments"><i class="ficon" data-feather="calendar"></i></a></li>
                @elseif(Auth::user()->user_type == "reception")
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{ route('reception.home') }}"
                        data-toggle="tooltip" data-placement="top" title="Dashboard"><i class="ficon"
                            data-feather="home"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"
                        href="{{ route('reception.appointment') }}" data-toggle="tooltip" data-placement="top"
                        title="Appointments"><i class="ficon" data-feather="calendar"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"
                        href="{{ route('reception.patient') }}" data-toggle="tooltip" data-placement="top"
                        title="Patients"><i class="ficon" data-feather="user-x"></i></a></li>
                @endif
                @endif
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">

            <!-- Languages -->
            <li class="nav-item dropdown dropdown-language">
                <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="flag-icon flag-icon-us"></i>
                    <span class="selected-language">{{ __('locale.enlang') }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                    <a class="dropdown-item" href="{{url('lang/en')}}" data-language="en">
                        <i class="flag-icon flag-icon-us"></i> {{ __('locale.enlang') }}
                    </a>
                    <a class="dropdown-item" href="{{url('lang/ar')}}" data-language="ar">
                        <i class="flag-icon flag-icon-sa"></i> {{ __('locale.arlang') }}
                    </a>
                </div>
            </li>
            <!-- Languages -->
            <!-- Notifications -->

            <!-- begin::notifications -->
            <li class="nav-item dropdown dropdown-notification mr-25"><a class="nav-link" href="javascript:void(0);"
                    data-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span
                        class="badge badge-pill badge-danger badge-up" id="INDICE_TOTAL_NOTIFICATIONS1">5</span></a>

                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                            <div class="badge badge-pill badge-light-primary" id="INDICE_TOTAL_NOTIFICATIONS2">6 New</div>
                        </div>
                    </li>
                    <li class="scrollable-container media-list" id="NOTIFICATION_DROPDOWN_CONTENT">
                       
                    </li>
                    <li style="display:none;" id="LI_BUTTON_READ_ALL_NOTIFICATION" class="dropdown-menu-footer"><a class="btn btn-primary btn-block" href="/notifications/month">Read all notifications</a></li>
                </ul>
            </li>
            <!-- end::notifications -->

            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="sun"></i></a></li>
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i
                        class="ficon feather icon-maximize" data-feather="maximize"></i></a></li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                    id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span
                            class="user-name font-weight-bolder">{{ Auth::user()->name }}</span><span
                            class="user-status">{{ Auth::user()->username }}</span></div><span class="avatar">
                        <!-- <img class="round" src="{{ asset('new-assets/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40" width="40"> -->
                        <img class="round" src="/storage/users-avatar/{{Auth::user()->avatar}}" alt="avatar" height="40"
                            width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">

                    <a class="dropdown-item" href="{{ route('account_setting') }}"><i class="mr-50"
                            data-feather="settings"></i> {{ __('locale.settings') }}</a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        href="{{ route('logout') }}"><i class="mr-50" data-feather="power"></i>
                        {{ __('locale.logout') }}</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- END: Header-->