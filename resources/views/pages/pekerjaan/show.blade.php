<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pekerjaan</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pages.pekerjaan') }}">Pekerjaan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Pekerjaan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="card-title">{{ $pekerjaan->nama_pekerjaan }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="info-group">
                                <label class="fw-bold text-muted">Volume</label>
                                <div class="fs-5">{{ $pekerjaan->volume }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-group">
                                <label class="fw-bold text-muted">Satuan</label>
                                <div class="fs-5">{{ $pekerjaan->satuan }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-group">
                                <label class="fw-bold text-muted">Sub Unit</label>
                                <div class="fs-5">{{ $pekerjaan->sub_unit }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-group">
                                <label class="fw-bold text-muted">Jenis OP</label>
                                <div class="fs-5">{{ $pekerjaan->jenis_op }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-group">
                                <label class="fw-bold text-muted">Nilai Paket Pekerjaan</label>
                                <div class="fs-5 fw-bold text-success">Rp {{ number_format($pekerjaan->nilai_paket_pekerjaan, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-group">
                                <label class="fw-bold text-muted">Divisi</label>
                                <div class="fs-5">{{ $pekerjaan->div }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Jadwal & Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="info-group">
                                                <label class="fw-bold text-muted">Jadwal Mulai</label>
                                                <div class="fs-5">{{ date('d F Y', strtotime($pekerjaan->jadwal_mulai)) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="info-group">
                                                <label class="fw-bold text-muted">Jadwal Selesai</label>
                                                <div class="fs-5">{{ date('d F Y', strtotime($pekerjaan->jadwal_selesai)) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-group">
                                                <label class="fw-bold text-muted">Status Proses</label>
                                                <div class="badge bg-primary fs-6 mt-1">{{ $pekerjaan->status_proses }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-group">
                                                <label class="fw-bold text-muted">Status GR</label>
                                                <div class="badge bg-info fs-6 mt-1">{{ $pekerjaan->status_gr }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-group">
                                                <label class="fw-bold text-muted">Jenis Investasi</label>
                                                <div class="badge bg-secondary fs-6 mt-1">{{ $pekerjaan->jenis_investasi }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('pages.pekerjaan') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <style>
        .info-group {
            margin-bottom: 8px;
        }
        .info-group label {
            display: block;
            margin-bottom: 4px;
            font-size: 0.9rem;
        }
    </style>
</x-app-layout>