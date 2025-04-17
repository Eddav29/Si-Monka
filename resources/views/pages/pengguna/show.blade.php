<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-last">
                <h3>Detail Pengguna</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pages.pengguna') }}">Pengguna</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h4 class="card-title mb-0">Informasi Pengguna</h4>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <p class="form-control-static fs-6">{{ $data['name'] }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <p class="form-control-static fs-6">{{ $data['email'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dibuat Pada</label>
                                        <p class="form-control-static fs-6">{{ \Carbon\Carbon::parse($data['created_at'])->format('d M Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Diperbarui Pada</label>
                                        <p class="form-control-static fs-6">{{ \Carbon\Carbon::parse($data['updated_at'])->format('d M Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 d-flex justify-content-start mt-4">
                                    <a href="{{ route('pages.pengguna') }}" class="btn btn-light-secondary me-2">
                                        <i class="bi bi-arrow-left-circle"></i> Kembali
                                    </a>
                                    <a href="{{ route('pages.pengguna.edit', $data['id']) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil-square"></i> Edit Pengguna
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
