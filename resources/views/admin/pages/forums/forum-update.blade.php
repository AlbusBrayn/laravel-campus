@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Forum Güncelle</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Annasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.forums') }}">Forumlar</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a>Forum Güncelle</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <div class="flex gap-x-2">
                    <button onclick="document.getElementById('postForm').submit()" class="btn btn_primary uppercase">Kaydet</button>
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
        <form id="postForm" action="{{ route('admin.forums.updateStore', ['post' => $post]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid lg:grid-cols-4 gap-5">
                <div class="lg:col-span-2 xl:col-span-3">
                    <div class="card p-5">
                        <div class="mb-5">
                            <label class="label block mb-2" for="user">Kullanıcı</label>
                            <div class="custom-select">
                                <select id="user" name="user" class="form-control" disabled>
                                    <option>{{ $post->user->name }}</option>
                                </select>
                                <div class="custom-select-icon la la-caret-down"></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="title">Başlık</label>
                            <div class="custom-select">
                                <select id="title" name="title" class="form-control" disabled>
                                    <option>{{ $post->postTitle->title }}</option>
                                </select>
                                <div class="custom-select-icon la la-caret-down"></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="short_content">Kısa İçerik</label>
                            <input type="text" id="short_content" name="short_content" class="form-control" value="{{ old('short_content', $post->short_content) }}">
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="long_content">İçerik</label>
                            <textarea id="long_content" name="long_content" class="form-control">{{ old('long_content', $post->content) }}</textarea>
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="like">Beğenme</label>
                            <input type="text" id="like" name="like" class="form-control" value="{{ $post->like }}" disabled>
                        </div>
                        <div class="mb-5">
                            <label class="label block mb-2" for="dislike">Beğenmeme</label>
                            <input type="text" id="dislike" name="dislike" class="form-control" value="{{ $post->dislike }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-y-5 lg:col-span-2 xl:col-span-1">
                    <div class="card p-5 flex flex-col gap-y-5">
                        <h3>Forum Durumu Ayarları</h3>
                        <div class="flex flex-col gap-y-5">
                            <div class="flex items-center">
                                <div class="w-3/4">
                                    <label class="label block">Yayında Mı?</label>
                                </div>
                                <div class="w-1/4 ml-2">
                                    <label class="switch">
                                        <input type="checkbox" name="published" @if($post->published) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3/4">
                                    <label class="label block">Aktif Mi?</label>
                                </div>
                                <div class="w-1/4 ml-2">
                                    <label class="switch">
                                        <input type="checkbox" name="is_active" @if($post->is_active) checked @endif>
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
