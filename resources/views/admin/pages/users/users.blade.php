@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Kullanıcı Listesi</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Annasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.users') }}">Kullanıcı Listesi</a></li>
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
                    <a href="{{ route('admin.users.create') }}" class="btn btn_primary uppercase">Yeni Ekle</a>
                </div>
            </div>
        </section>
        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full">
                    <thead>
                    <tr>
                        <th class="text-center uppercase">#</th>
                        <th class="text-center uppercase">Ad Soyad</th>
                        <th class="text-center uppercase">Email</th>
                        <th class="text-center uppercase">Okul</th>
                        <th class="text-center uppercase">Banlı Mı?</th>
                        <th class="text-center uppercase">Harita İzni?</th>
                        <th class="text-center uppercase">Hesap Durumu</th>
                        <th class="text-center uppercase">Susturulmuş Mu?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="text-center uppercase">{{ $user->id }}</td>
                            <td class="text-center uppercase">{{ $user->name }}</td>
                            <td class="text-center uppercase">{{ $user->email }}</td>
                            <td class="text-center uppercase">{{ $user->school->name }}</td>
                            <td class="text-center">
                                @if($user->is_banned)
                                    <div class="badge badge_success uppercase">Evet</div>
                                @else
                                    <div class="badge badge_danger uppercase">Hayır</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$user->hide_location)
                                    <div class="badge badge_success uppercase">Evet</div>
                                @else
                                    <div class="badge badge_danger uppercase">Hayır</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->is_active)
                                    <div class="badge badge_success uppercase">Aktif</div>
                                @else
                                    <div class="badge badge_danger uppercase">Deaktif</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->is_muted)
                                    <div class="badge badge_success uppercase">Evet</div>
                                @else
                                    <div class="badge badge_danger uppercase">Hayır</div>
                                @endif
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <a href="{{ route('admin.users.update', ['user' => $user]) }}" class="btn btn-icon btn_outlined btn_secondary">
                                        <span class="la la-pen-fancy"></span>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Bu kullanıcıyı silmek istediğine emin misin ?');" action="{{ route('admin.users.delete', ['user' => $user]) }}">
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
                {{  $users->links() }}
            </div>
        </div>
    </main>
@endsection
