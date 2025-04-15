<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pekerjaan</h3>
                <p class="text-subtitle text-muted">Form untuk menambahkan data pekerjaan baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pages.pekerjaan') }}">Pekerjaan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Tambah Pekerjaan</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pages.pekerjaan.store') }}" method="POST">
                                @csrf
                                
                                <!-- Form fields remain unchanged -->
                                
                                <div class="mt-4 text-end">
                                    <a href="{{ route('pages.pekerjaan') }}" class="btn btn-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
