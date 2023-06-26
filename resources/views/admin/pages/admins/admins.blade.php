@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Admin Listesi</h1>
                <ul>
                    <li><a class="{{ route('admin.dashboard') }}">Anasayfa</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.admins') }}">Admin Listesi</a></li>
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
                    <a href="{{ route('admin.admins.create') }}" class="btn btn_primary uppercase">Yeni Ekle</a>
                </div>
            </div>
        </section>
        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full">
                    <thead>
                    <tr>
                        <th class="text-center uppercase">Name</th>
                        <th class="text-center uppercase">Email</th>
                        <th class="text-center uppercase">IP Address</th>
                        <th class="text-center uppercase">Last Login</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td class="text-center uppercase">{{ $admin->name }}</td>
                            <td class="text-center uppercase">{{ $admin->email }}</td>
                            <td class="text-center uppercase">{{ $admin->ip_address }}</td>
                            <td class="text-center uppercase">{{ $admin->last_login }}</td>
                            <td class="text-center whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <a href="{{ route('admin.admins.update', ['admin' => $admin]) }}" class="btn btn-icon btn_outlined btn_secondary">
                                        <span class="la la-pen-fancy"></span>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Do you really want to delete this item?');" action="{{ route('admin.admins.delete', ['admin' => $admin]) }}">
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
                {{  $admins->links() }}
            </div>
        </div>
    </main>
@endsection
