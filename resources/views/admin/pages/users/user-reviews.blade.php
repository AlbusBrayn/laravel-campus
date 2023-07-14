@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Kullanıcı Değerlendirmeleri</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.users.reviews') }}">Kullanıcı Değerlendirmeleri</a></li>
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
                        <th class="text-center uppercase">Öğretmen</th>
                        <th class="text-center uppercase">İletişim</th>
                        <th class="text-center uppercase">Ders Anlatımı</th>
                        <th class="text-center uppercase">Puanlama</th>
                        <th class="text-center uppercase">Yorum</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teacherVotes as $vote)
                        <tr>
                            <td class="text-center uppercase">{{ $vote->user->name }}</td>
                            <td class="text-center uppercase">{{ $vote->teacher->name }}</td>
                            <td class="text-center uppercase">{{ $vote->quality }}</td>
                            <td class="text-center uppercase">{{ $vote->attitude }}</td>
                            <td class="text-center uppercase">{{ $vote->performance }}</td>
                            <td class="text-center uppercase">{{ $vote->comment }}</td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <a href="" class="btn btn-icon btn_outlined btn_secondary">
                                        <span class="la la-pen-fancy"></span>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bu kullanıcı değerlendirmesini silmek istediğine emin misin ?');" action="{{ route('admin.users.reviews.delete', ['review' => $vote]) }}">
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
                {{  $teacherVotes->links() }}
            </div>
        </div>
    </main>
@endsection
