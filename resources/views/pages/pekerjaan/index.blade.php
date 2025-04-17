<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pekerjaan</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-data"><a href="{{ route('dashboard') }}">Dashboard&nbsp</a></li>
                        <li class="breadcrumb-data active" aria-current="page"> - Pekerjaan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Daftar Pekerjaan</h4>
                        <a href="{{ route('pages.pekerjaan.create') }}" class="btn btn-primary">Tambah Pekerjaan</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pages.pekerjaan.create') }}" method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabelPekerjaan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pekerjaan</th>
                                        <th>Sub Unit</th>
                                        <th>Jenis</th>
                                        <th>Nilai Paket (Rp)</th>
                                        <th>Jadwal Mulai</th>
                                        <th>Jadwal Selesai</th>
                                        <th>Status Proses</th>
                                        <th>Status GR</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key => $pekerjaan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $pekerjaan['nama_pekerjaan'] }}</td>
                                        <td>{{ $pekerjaan['sub_unit'] }}</td>
                                        <td>{{ $pekerjaan['jenis_op'] }}</td>
                                        <td>{{ number_format($pekerjaan['nilai_paket_pekerjaan'], 0, ',', '.') }}</td>
                                        <td>{{ date('d F Y', strtotime($pekerjaan['jadwal_mulai'])) }}</td>
                                        <td>{{ date('d F Y', strtotime($pekerjaan['jadwal_selesai'])) }}</td>
                                        <td>
                                            <span class="badge {{ $pekerjaan['status_proses'] == 'Belum Dimulai' ? 'bg-secondary' : ($pekerjaan['status_proses'] == 'Dalam Proses' ? 'bg-primary' : 'bg-success') }}">
                                                {{ $pekerjaan['status_proses'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $pekerjaan['status_gr'] == 'Sudah GR' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $pekerjaan['status_gr'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('pages.pekerjaan.show', $pekerjaan['id']) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('pages.pekerjaan.edit', $pekerjaan['id']) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pages.pekerjaan.destroy', $pekerjaan['id']) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pekerjaan ini?')">
                                                    <i class="bi bi-trash"></i>
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
                </div>
            </div>
        </div>
    </section>
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabelPekerjaan').DataTable();
        });
    </script>
    @endpush
</x-app-layout>
