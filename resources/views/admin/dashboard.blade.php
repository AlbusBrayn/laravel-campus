@extends('admin._layouts.dashboard-template')
@section('content')
    <main class="workspace overflow-hidden">
        <section class="breadcrumb">
            <h1>Anasayfa</h1>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}">Anasayfa</a></li>
            </ul>
        </section>

        <div class="grid lg:grid-cols-2 gap-5">
            <div class="grid sm:grid-cols-3 gap-5">
                <div
                    class="card px-4 py-8 flex justify-center items-center text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                    <div>
                        <span class="text-primary text-5xl leading-none la la-user"></span>
                        <p class="mt-2">Kullanıcı Sayısı</p>
                        <div class="text-primary mt-5 text-3xl leading-none">21</div>
                    </div>
                </div>
                <div
                    class="card px-4 py-8 flex justify-center items-center text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                    <div>
                        <span class="text-primary text-5xl leading-none la la-school"></span>
                        <p class="mt-2">Okul Sayısı</p>
                        <div class="text-primary mt-5 text-3xl leading-none">1</div>
                    </div>
                </div>
                <div
                    class="card px-4 py-8 flex justify-center items-center text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                    <div>
                        <span class="text-primary text-5xl leading-none la la-book"></span>
                        <p class="mt-2">Bölüm Sayısı</p>
                        <div class="text-primary mt-5 text-3xl leading-none">4</div>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-3 gap-5">
                <div
                    class="card px-4 py-8 flex justify-center items-center text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                    <div>
                        <span class="text-primary text-5xl leading-none la la-pencil"></span>
                        <p class="mt-2">Ders Sayısı</p>
                        <div class="text-primary mt-5 text-3xl leading-none">8</div>
                    </div>
                </div>
                <div
                    class="card px-4 py-8 flex justify-center items-center text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                    <div>
                        <span class="text-primary text-5xl leading-none la la-chalkboard-teacher"></span>
                        <p class="mt-2">Öğretmen Sayısı</p>
                        <div class="text-primary mt-5 text-3xl leading-none">16</div>
                    </div>
                </div>
                <div
                    class="card px-4 py-8 flex justify-center items-center text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                    <div>
                        <span class="text-primary text-5xl leading-none la la-cog"></span>
                        <p class="mt-2">Tracker Injected</p>
                        <div class="text-primary mt-5 text-3xl leading-none">18</div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file">Excel Dosyası Yükle:</label>
            <input type="file" id="file" name="file" required>
            <button type="submit" class="btn btn-primary">İçeri Aktar</button>
        </form>

        <footer class="mt-auto">
            <div class="footer">
                <span class='uppercase'>&copy; 2022 Campus A+</span>
            </div>
        </footer>
    </main>
@endsection
@section('script')
    <script src="{{ asset('assets/js/glide.min.js') }}"></script>
@endsection
