@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Admin Oluştur</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.admins') }}">Admin Listesi</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a>Admin Oluştur</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <div class="flex gap-x-2">
                    <button onclick="document.getElementById('adminForm').submit()" class="btn btn_primary uppercase">Kaydet</button>
                </div>
            </div>
        </section>
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert_danger mb-2">
                    <strong class="uppercase"><bdi>Error!</bdi></strong>
                    {{ $error }}
                    <button class="dismiss la la-times" data-dismiss="alert"></button>
                </div>
            @endforeach
        @endif
        <form id="adminForm" action="{{ route('admin.admins.createStore') }}" method="POST">
            @csrf
            <div class="w-full gap-5">
                <div class="card p-5">
                    <div class="mb-5">
                        <label class="label block mb-2" for="name">Ad</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="password">Şifre</label>
                        <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="re_password">Şifre Tekrar</label>
                        <input type="password" id="re_password" name="re_password" class="form-control" value="{{ old('re_password') }}">
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
