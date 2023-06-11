@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Change Password</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Dashborad</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.changePassword') }}">Change Password</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <div class="flex gap-x-2">
                    <button onclick="document.getElementById('changePasswordForm').submit()" class="btn btn_primary uppercase">Save</button>
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
        <form id="changePasswordForm" action="{{ route('admin.changePasswordStore') }}" method="POST">
            @csrf
            <div class="w-full gap-5">
                <div class="card p-5">
                    <div class="mb-5">
                        <label class="label block mb-2" for="old_password">Old Password</label>
                        <input type="password" id="old_password" name="old_password" class="form-control" value="{{ old('old_password') }}">
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
