@php
$defaultLogos=\App\Library\Helpers\Helper::getDefaultLogos();
$sidebar_logo=\App\Library\Helpers\Helper::getSetting('app','sidebar_logo');
$logo=$defaultLogos['sidebar_logo'];
if(isset($sidebar_logo) && !empty($sidebar_logo)){
    $logo=$sidebar_logo;
}
@endphp
<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href=""><span class="brand-logo">

                <img style="max-width:100px;max-height:26px;" src="{{asset($logo)}}" alt="logo">
                
                </span>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @if(Auth::user())
                @if(Auth::user()->user_type == "admin")
                
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.home' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.home') }}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.services' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.services') }}"><i data-feather="list"></i><span class="menu-title text-truncate" data-i18n="Services">Services</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.users' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.users') }}"><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Users">Users</span></a></li>
                <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i data-feather="user-plus"></i><span class="menu-title text-truncate" data-i18n="Doctors">Doctors</span></a>
                    <ul class="menu-content">
                        <li class="{{ Route::currentRouteName() === 'admin.doctor' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.doctor') }}"><i data-feather="user-plus"></i><span class="menu-item" data-i18n="Doctors">Doctors</span></a>
                        </li>
                        <li class="{{ Route::currentRouteName() === 'admin.schedules' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.schedules') }}"><i data-feather="clock"></i><span class="menu-item" data-i18n="Schedule Timings">Schedule Timings</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.patient' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.patient') }}"><i data-feather="user-x"></i><span class="menu-title text-truncate" data-i18n="Patients">Patients</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.reports' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.reports') }}"><i data-feather="bar-chart-2"></i><span class="menu-title text-truncate" data-i18n="Reports">Reports</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.appointment' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.appointment') }}"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Appointments">Appointments</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.officetime' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.officetime') }}"><i data-feather="clock"></i><span class="menu-title text-truncate" data-i18n="Office Time">Office Time</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.clinic' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.clinic') }}"><i data-feather="activity"></i><span class="menu-title text-truncate" data-i18n="Clinic">Clinic</span></a></li>
                
                <li class=" nav-item "><a class="d-flex align-items-center" href="#"><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Settings">Settings</span></a>
                    <ul class="menu-content">
                        <li class="{{ Route::currentRouteName() === 'admin.settings.general' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.settings.general') }}"><i data-feather="edit"></i><span class="menu-item" data-i18n="Doctors">General</span></a>
                        </li>
                        <!-- <li class="{{ Route::currentRouteName() === 'admin.schedules' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.schedules') }}"><i data-feather="clock"></i><span class="menu-item" data-i18n="Schedule Timings">Schedule Timings</span></a>
                        </li> -->
                    </ul>
                </li>

                <li class=" nav-item {{ Route::currentRouteName() === 'admin.logs' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.logs') }}"><i data-feather="archive"></i><span class="menu-title text-truncate" data-i18n="Activities logs">Logs</span></a></li>   
                <li class=" nav-item {{ Route::currentRouteName() === 'admin.backup' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.backup') }}"><i data-feather="hard-drive"></i><span class="menu-title text-truncate" data-i18n="Backup">Backup</span></a></li>   

                @elseif(Auth::user()->user_type == "doctor")
                <li class=" nav-item {{ Route::currentRouteName() === 'doctor.home' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('doctor.home') }}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'doctor.appointment' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('doctor.appointment') }}"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Appointments">Appointments</span></a></li>
                @elseif(Auth::user()->user_type == "reception")
                <li class=" nav-item {{ Route::currentRouteName() === 'reception.home' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('reception.home') }}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'reception.appointment' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('reception.appointment') }}"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Appointments">Appointments</span></a></li>
                <li class=" nav-item {{ Route::currentRouteName() === 'reception.patient' ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('reception.patient') }}"><i data-feather="user-x"></i><span class="menu-title text-truncate" data-i18n="Patients">Patients</span></a></li>
                @endif
            @endif

            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->