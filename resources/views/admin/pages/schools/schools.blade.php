@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Okul Listesi</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.schools') }}">Okul Listesi</a></li>
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
                <div class="flex gap-x-2">
                    <a href="{{ route('admin.schools.create') }}" class="btn btn_primary uppercase">Yeni Ekle</a>
                </div>
            </div>
        </section>
        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full">
                    <thead>
                    <tr>
                        <th class="text-center uppercase">Okul Adı</th>
                        <th class="text-center uppercase">Email Paterni</th>
                        <th class="text-center uppercase">Enlem</th>
                        <th class="text-center uppercase">Boylam</th>
                        <th class="text-center uppercase">Enlem Delta</th>
                        <th class="text-center uppercase">Boylam Delta</th>
                        <th class="text-center uppercase">Aktif Mi?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($schools as $school)
                        <tr>
                            <td class="text-center uppercase">{{ $school->name }}</td>
                            <td class="text-center uppercase">{{ $school->email_pattern }}</td>
                            <td class="text-center uppercase">{{ $school->latitude }}</td>
                            <td class="text-center uppercase">{{ $school->longitude }}</td>
                            <td class="text-center uppercase">{{ $school->latitude_delta }}</td>
                            <td class="text-center uppercase">{{ $school->longitude_delta }}</td>
                            <td class="text-center">
                                @if($school->is_active)
                                    <div class="badge badge_success uppercase">Aktif</div>
                                @else
                                    <div class="badge badge_danger uppercase">Deaktif</div>
                                @endif
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <a href="{{ route('admin.schools.update', ['school' => $school]) }}" class="btn btn-icon btn_outlined btn_secondary">
                                        <span class="la la-pen-fancy"></span>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bu okulu silmek istediğinden emin misin?');" action="{{ route('admin.schools.delete', ['school' => $school]) }}">
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
                {{  $schools->links() }}
            </div>
        </div>
    </main>
@endsection
