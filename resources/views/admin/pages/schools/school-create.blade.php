@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Okul Oluştur</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Annasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.schools') }}">Okul Listesi</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a>Okul Oluştur</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <div class="flex gap-x-2">
                    <button onclick="document.getElementById('schoolForm').submit()" class="btn btn_primary uppercase">Kaydet</button>
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
        <form id="schoolForm" action="{{ route('admin.schools.createStore') }}" method="POST">
            @csrf
            <div class="grid lg:grid-cols-4 gap-5">
                <div class="lg:col-span-2 xl:col-span-3">
                    <div class="card p-5">
                        <div class="mb-5">
                            <label class="label block mb-2" for="name">Okul Adı</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="email_pattern">Email Paterni</label>
                            <input type="text" id="email_pattern" name="email_pattern" class="form-control" value="{{ old('email_pattern') }}">
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="latitude">Enlem</label>
                            <input type="text" id="latitude" name="latitude" class="form-control" value="{{ old('latitude') }}">
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="longitude">Boylam</label>
                            <input type="text" id="longitude" name="longitude" class="form-control" value="{{ old('longitude') }}">
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="latitude_delta">Enlem Delta</label>
                            <input type="text" id="latitude_delta" name="latitude_delta" class="form-control" value="{{ old('latitude_delta') }}">
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="longitude_delta">Boylam Delta</label>
                            <input type="text" id="longitude_delta" name="longitude_delta" class="form-control" value="{{ old('longitude_delta') }}">
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-y-5 lg:col-span-2 xl:col-span-1">
                    <div class="card p-5 flex flex-col gap-y-5">
                        <h3>Okul Ayarları</h3>
                        <div class="flex flex-col gap-y-5">
                            <div class="flex items-center">
                                <div class="w-3/4">
                                    <label class="label block">Aktif ?</label>
                                </div>
                                <div class="w-1/4 ml-2">
                                    <label class="switch">
                                        <input type="checkbox" name="is_active">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
