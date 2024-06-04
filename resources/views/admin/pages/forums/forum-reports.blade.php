@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Forum Şikayet Listesi</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Annasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.forums') }}">Forum Şikayet Listesi</a></li>
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
                        <th class="text-center uppercase">Şikayet Eden</th>
                        <th class="text-center uppercase">Şikayet Edilen</th>
                        <th class="text-center uppercase">Sebep</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($forumReports as $forumReport)
                        <tr>
                            <td class="text-center uppercase"><a href="{{ route('admin.users.update', ['user' => $forumReport->user]) }}">{{ $forumReport->user->name }}</a></td>
                            <td class="text-center uppercase"><a href="{{ route('admin.forums.update', ['post' => $forumReport->post]) }}">{{ Str::limit($forumReport->post->short_content, 25, '...') }}</a></td>
                            <td class="text-center uppercase">{{ getReportReasons()[$forumReport->reason_id] }}</td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <form method="POST" onsubmit="return confirm('Bu şikayeti silmek istediğinden emin misin?');" action="{{ route('admin.forums.reports.delete', ['report' => $forumReport]) }}">
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
                {{  $forumReports->links() }}
            </div>
        </div>
    </main>
@endsection
