@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Forum Listesi</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.forums') }}">Forum Listesi</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap gap-2 items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-0">
                <form class="flex flex-auto">
                    <label class="form-control-addon-within rounded-full">
                        <input class="form-control border-none" placeholder="Search">
                        <button
                            class="text-gray-300 dark:text-gray-700 text-xl leading-none la la-search ltr:mr-4 rtl:ml-4"></button>
                    </label>
                </form>
            </div>
        </section>
        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full">
                    <thead>
                    <tr>
                        <th class="text-center uppercase">Konu Sahibi</th>
                        <th class="text-center uppercase">Konu Başlığı</th>
                        <th class="text-center uppercase">Kısa İçerik</th>
                        <th class="text-center uppercase">Beğenme</th>
                        <th class="text-center uppercase">Beğenmeme</th>
                        <th class="text-center uppercase">Paylaşım Durumu</th>
                        <th class="text-center uppercase">Aktif Mi?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($forums as $forum)
                        <tr>
                            <td class="text-center uppercase"><a href="{{ route('admin.users.update', ['user' => $forum->user]) }}">{{ $forum->user->name }}</a></td>
                            <td class="text-center uppercase"><a href="">{{ $forum->postTitle->title }}</a></td>
                            <td class="text-center uppercase">{{ Str::limit($forum->short_content, 50, '...') }}</td>
                            <td class="text-center uppercase">{{ $forum->like }}</td>
                            <td class="text-center uppercase">{{ $forum->dislike }}</td>
                            <td class="text-center">
                                @if($forum->published)
                                    <div class="badge badge_success uppercase">Aktif</div>
                                @else
                                    <div class="badge badge_danger uppercase">Deaktif</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($forum->is_active)
                                    <div class="badge badge_success uppercase">Aktif</div>
                                @else
                                    <div class="badge badge_danger uppercase">Deaktif</div>
                                @endif
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <a href="{{ route('admin.forums.update', ['post' => $forum]) }}" class="btn btn-icon btn_outlined btn_secondary">
                                        <span class="la la-pen-fancy"></span>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bu forumu silmek istediğinden emin misin?');" action="{{ route('admin.forums.delete', ['post' => $forum]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn_outlined btn_danger ltr:ml-2 rtl:mr-2">
                                            <span class="la la-trash-alt"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">
            <div class="card lg:flex">
                {{  $forums->links() }}
            </div>
        </div>
    </main>
@endsection
