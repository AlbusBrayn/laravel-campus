@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Kullanıcı Yorumları</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.users.comments') }}">Kullanıcı Yorumları</a></li>
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
                        <th class="text-center uppercase">Kullanıcı</th>
                        <th class="text-center uppercase">Post</th>
                        <th class="text-center uppercase">Üst Yorum</th>
                        <th class="text-center uppercase">Beğenme</th>
                        <th class="text-center uppercase">Beğenmeme</th>
                        <th class="text-center uppercase">Yorum</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <td class="text-center uppercase"><a href="{{ route('admin.users.update', ['user' => $comment->user]) }}">{{ $comment->user->name }}</a></td>
                            <td class="text-center uppercase">{{ $comment->post->title }}</td>
                            <td class="text-center uppercase">
                                @if($comment->parent_id !== null)
                                    <a href="">{{ $comment->parent->body }}</a>
                                @else
                                    <div class="badge badge_danger uppercase">Hayır</div>
                                @endif
                            </td>
                            <td class="text-center uppercase">{{ $comment->like_count }}</td>
                            <td class="text-center uppercase">{{ $comment->dislike_count }}</td>
                            <td class="text-center uppercase">{{ $comment->body }}</td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <a href="" class="btn btn-icon btn_outlined btn_secondary">
                                        <span class="la la-pen-fancy"></span>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bu kullanıcı yorumunu silmek istediğine emin misin ?');" action="{{ route('admin.users.comments.delete', ['comment' => $comment]) }}">
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
                {{  $comments->links() }}
            </div>
        </div>
    </main>
@endsection
