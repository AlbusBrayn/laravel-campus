@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Bildirim Gönder</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a>Bildirim Gönder</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <div class="flex gap-x-2">
                    <button onclick="document.getElementById('sendNotificationForm').submit()" class="btn btn_primary uppercase">Kaydet</button>
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
        <form id="sendNotificationForm" action="{{ route('admin.sendNotificationStore') }}" method="POST">
            @csrf
            <div class="w-full gap-5">
                <div class="card p-5">
                    <div class="mb-5">
                        <label class="label block mb-2" for="school">Kullanıcı Seç</label>
                        <div class="custom-select">
                            <select id="school" name="school_id" class="form-control" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div class="custom-select-icon la la-caret-down"></div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="title">Başlık</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}">
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="message">Mesaj</label>
                        <input type="text" id="message" name="message" class="form-control" value="{{ old('message') }}">
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
