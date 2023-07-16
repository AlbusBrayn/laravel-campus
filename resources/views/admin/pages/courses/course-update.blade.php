@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Ders Güncelle</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.courses') }}">Ders Listesi</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a>Ders Güncelle</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <div class="flex gap-x-2">
                    <button onclick="document.getElementById('courseForm').submit()" class="btn btn_primary uppercase">Kaydet</button>
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
        <form id="courseForm" action="{{ route('admin.courses.updateStore', ['course' => $course]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid lg:grid-cols-4 gap-5">
                <div class="lg:col-span-2 xl:col-span-3">
                    <div class="card p-5">
                        <div class="mb-5">
                            <label class="label block mb-2" for="school">Okul</label>
                            <div class="custom-select">
                                <select id="school" name="school_id" class="form-control">
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" @if($school->id === $course->school_id) selected @endif>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                <div class="custom-select-icon la la-caret-down"></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="major">Bölüm</label>
                            <div class="custom-select">
                                <select id="major" name="major_id" class="form-control">
                                    @foreach($majors as $major)
                                        <option value="{{ $major->id }}" @if($major->id === $course->major_id) selected @endif>{{ $major->title }}</option>
                                    @endforeach
                                </select>
                                <div class="custom-select-icon la la-caret-down"></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="name">Ders Adı</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $course->name) }}">
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-y-5 lg:col-span-2 xl:col-span-1">
                    <div class="card p-5 flex flex-col gap-y-5">
                        <h3>Ders Ayarları</h3>
                        <div class="flex flex-col gap-y-5">
                            <div class="flex items-center">
                                <div class="w-3/4">
                                    <label class="label block">Aktif ?</label>
                                </div>
                                <div class="w-1/4 ml-2">
                                    <label class="switch">
                                        <input type="checkbox" name="is_active" @if($course->is_active) checked @endif>
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
