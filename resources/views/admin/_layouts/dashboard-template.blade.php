@php
    $user = auth()->guard('admin')->user();
@endphp

    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Campus A+ - Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>

<body>
<header class="top-bar">
    <button class="menu-toggler la la-bars" data-toggle="menu"></button>
    <span class="brand">Campus A+</span>
    <div class="flex items-center ltr:ml-auto rtl:mr-auto">
        <label class="switch switch_outlined" data-toggle="tooltip" data-tippy-content="Toggle Dark Mode">
            <input id="darkModeToggler" type="checkbox">
            <span></span>
        </label>
        <button id="fullScreenToggler"
                class="hidden lg:inline-block ltr:ml-3 rtl:mr-3 px-2 text-2xl leading-none la la-expand-arrows-alt"
                data-toggle="tooltip" data-tippy-content="Fullscreen"></button>

        <div class="dropdown self-stretch">
            <button class="relative flex items-center h-full ltr:ml-1 rtl:mr-1 px-2 text-2xl leading-none la la-bell"
                    data-toggle="custom-dropdown-menu" data-tippy-arrow="true" data-tippy-placement="bottom-end">
                    <span
                        class="absolute top-0 right-0 rounded-full border border-primary -mt-1 -mr-1 px-2 leading-tight text-xs font-body text-primary">3</span>
            </button>
            <div class="custom-dropdown-menu">
                <div class="flex items-center px-5 py-2">
                    <h5 class="mb-0 uppercase">Bildirimler</h5>
                    <button class="btn btn_outlined btn_warning uppercase ltr:ml-auto rtl:mr-auto">Hepsini Temizle</button>
                </div>
                <hr>
                <div class="p-5 hover:bg-primary hover:bg-opacity-5">
                    <a href="#no-link">
                        <h6 class="uppercase">Test Bildirimi</h6>
                    </a>
                    <p>Lorem ipsum dolor, sit amet consectetur.</p>
                    <small>26.06.2023</small>
                </div>
                <hr>
            </div>
        </div>

        <div class="dropdown">
            <button class="flex items-center ltr:ml-4 rtl:mr-4" data-toggle="custom-dropdown-menu"
                    data-tippy-arrow="true" data-tippy-placement="bottom-end">
                <span class="avatar">TA</span>
            </button>
            <div class="custom-dropdown-menu w-64">
                <div class="p-5">
                    <h5 class="uppercase">{{ $user->name }}</h5>
                    <p>{{ $user->email }}</p>
                </div>
                <hr>
                <div class="p-5">
                    <a href="#no-link" class="flex items-center text-normal hover:text-primary">
                        <span class="la la-user-circle text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>
                        Profili Görüntüle
                    </a>
                    <a href="{{ route('admin.changePassword') }}" class="flex items-center text-normal hover:text-primary mt-5">
                        <span class="la la-key text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>
                        Şifreyi Değiştir
                    </a>
                </div>
                <hr>
                <div class="p-5">
                    <a href="{{ route('admin.logout') }}" class="flex items-center text-normal hover:text-primary">
                        <span class="la la-power-off text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>
                        Çıkış Yap
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<aside class="menu-bar menu-sticky">
    <div class="menu-items">
        <a href="{{ route('admin.dashboard') }}" class="link active" data-toggle="tooltip-menu" data-tippy-content="Dashboard">
            <span class="icon la la-laptop"></span>
            <span class="title">Anasayfa</span>
        </a>
        <button class="link" data-target="[data-menu=staffs]" data-toggle="tooltip-menu" data-tippy-content="Staffs">
            <span class="icon la la-users"></span>
            <span class="title">Yetkili Yönetimi</span>
        </button>
        <button class="link" data-target="[data-menu=system]" data-toggle="tooltip-menu" data-tippy-content="System">
            <span class="icon la la-cogs"></span>
            <span class="title">Sistem Yönetimi</span>
        </button>
        <button class="link" data-target="[data-menu=tracker]" data-toggle="tooltip-menu" data-tippy-content="Tracker">
            <span class="icon la la-user-tag"></span>
            <span class="title">Tracker Management</span>
        </button>
        <button class="link" data-target="[data-menu=schools]" data-toggle="tooltip-menu" data-tippy-content="Schools">
            <span class="icon la la-school"></span>
            <span class="title">Okul Yönetimi</span>
        </button>
        <button class="link" data-target="[data-menu=forums]" data-toggle="tooltip-menu" data-tippy-content="Forums">
            <span class="icon la la-blog"></span>
            <span class="title">Forum Yönetimi</span>
        </button>
    </div>
    <div class="menu-detail" data-menu="staffs">
        <div class="menu-detail-wrapper">
            <h6 class="uppercase">Yetkili Yönetimi</h6>
            <a href="{{ route('admin.admins') }}">
                <span class="la la-list"></span>
                Admin Listesi
            </a>
            <a href="{{ route('admin.admins.create') }}">
                <span class="la la-plus-circle"></span>
                Admin Oluştur
            </a>
        </div>
    </div>
    <div class="menu-detail" data-menu="system">
        <div class="menu-detail-wrapper">
            <h6 class="uppercase">Sistem Yönetimi</h6>
            <a href="{{ route('admin.users') }}">
                <span class="la la-list"></span>
                Kullanıcılar
            </a>
            <a href="{{ route('admin.users.reviews') }}">
                <span class="la la-list"></span>
                Kullanıcı Değerlendirmeleri
            </a>
            <a href="">
                <span class="la la-list"></span>
                Kullanıcı Yasakları
            </a>
            <a href="{{ route('admin.users.comments') }}">
                <span class="la la-list"></span>
                Kullanıcı Yorumları
            </a>
            <a href="">
                <span class="la la-list"></span>
                Kullanıcı Mesajları
            </a>
            <a href="#">
                <span class="la la-list"></span>
                Şikayet Edilen Kullanıcılar
            </a>
        </div>
    </div>
    <div class="menu-detail" data-menu="tracker">
        <div class="menu-detail-wrapper">
            <h6 class="uppercase">Tracker</h6>
            <a href="">
                <span class="la la-list"></span>
                Kullanıcı Verileri
            </a>
            <a href="">
                <span class="la la-list"></span>
                Kullanıcı Çerezleri
            </a>
        </div>
    </div>
    <div class="menu-detail" data-menu="schools">
        <div class="menu-detail-wrapper">
            <h6 class="uppercase">Okul Yönetimi</h6>
            <a href="{{ route('admin.schools') }}">
                <span class="la la-list"></span>
                Okul Listesi
            </a>
            <a href="{{ route('admin.majors') }}">
                <span class="la la-list"></span>
                Ders Listesi
            </a>
            <a href="#">
                <span class="la la-list"></span>
                Öğretmen Listesi
            </a>
        </div>
    </div>
    <div class="menu-detail" data-menu="forums">
        <div class="menu-detail-wrapper">
            <h6 class="uppercase">Forum Yönetimi</h6>
            <a href="{{ route('admin.forums.titles') }}">
                <span class="la la-list"></span>
                Forum Konuları
            </a>
            <a href="{{ route('admin.forums') }}">
                <span class="la la-list"></span>
                Forum Listesi
            </a>
            <a href="{{ route('admin.forums.reports') }}">
                <span class="la la-list"></span>
                Şikayet Edilen Forumlar
            </a>
        </div>
    </div>
</aside>
@yield('content')
<script src="{{ asset('assets/js/vendor.js') }}"></script>
@yield('script')
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session()->has('success'))
    <script>
        let toastMixin = Swal.mixin({
            toast: true,
            animation: false,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        toastMixin.fire({
            animation: true,
            title: '{{ session('success') }}',
            icon: 'success'
        });
    </script>
@endif
@if(session()->has('error'))
    <script>
        let toastMixin = Swal.mixin({
            toast: true,
            animation: false,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        toastMixin.fire({
            animation: true,
            title: '{{ session('error') }}',
            icon: 'error'
        });
    </script>
@endif
</body>
</html>
