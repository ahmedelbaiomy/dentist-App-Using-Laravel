<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/vendors.min.css') }}" />
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/ui/prism.min.css') }}" />
{{-- Vendor Styles --}}
@yield('vendor-style')
{{-- Theme Styles --}}
<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('new-assets/app-assets/css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('new-assets/app-assets/css/bootstrap-extended.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('new-assets/app-assets/css/colors.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('new-assets/app-assets/css/components.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('new-assets/app-assets/css/themes/dark-layout.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('new-assets/app-assets/css/themes/bordered-layout.css') }}">

{{-- Page Styles --}}
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}" />

{{-- Page Styles --}}
@yield('page-style')


{{-- user custom styles --}}
<link rel="stylesheet" href="{{ asset('new-assets/assets/css/style.css') }}" />
