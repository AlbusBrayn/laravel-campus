<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Campus A+ - Login</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>
<body>
<section class="top-bar">
    <span class="brand">Campus A+</span>
    <nav class="flex items-center ltr:ml-auto rtl:mr-auto">
        <label class="switch switch_outlined" data-toggle="tooltip" data-tippy-content="Toggle Dark Mode">
            <input id="darkModeToggler" type="checkbox">
            <span></span>
        </label>

        <button id="fullScreenToggler"
                class="hidden lg:inline-block ltr:ml-5 rtl:mr-5 text-2xl leading-none la la-expand-arrows-alt"
                data-toggle="tooltip" data-tippy-content="Fullscreen"></button>
    </nav>
</section>
@yield('content')
<script src="{{ asset('assets/js/vendor.js') }}"></script>
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
