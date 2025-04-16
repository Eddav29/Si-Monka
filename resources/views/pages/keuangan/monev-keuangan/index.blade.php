<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Monitoring dan Evaluasi Keuangan</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-data"><a href="{{ route('dashboard') }}">Dashboard&nbsp</a></li>
                        <li class="breadcrumb-data"><a href="#">Keuangan&nbsp</a></li>
                        <li class="breadcrumb-data active" aria-current="page"> - Monev Keuangan</li>
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
                        <h4>Daftar Monitoring dan Evaluasi Keuangan</h4>
                        <a href="{{route('pages.keuangan.monev-keuangan.create')}}" class="btn btn-primary">Tambah Monev Keuangan</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabelMonevKeuangan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pekerjaan</th>
                                        <th>Jenis Monitoring</th>
                                        <th>Status</th>
                                        <th>Program (Rp)</th>
                                        <th>Realisasi (Rp)</th>
                                        <th>PIC</th>
                                        <th>Periode</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td> <!-- Nomor urut -->
                                        <td>{{ $item['pekerjaan']['nama_pekerjaan'] ?? 'N/A' }}</td>
                                        <td>
                                            @switch($item['jenis_monitoring'])
                                                @case('perencanaan')
                                                    <span class="badge bg-primary">Perencanaan</span>
                                                    @break
                                                @case('verifikasi')
                                                    <span class="badge bg-info">Verifikasi</span>
                                                    @break
                                                @case('pengadaan')
                                                    <span class="badge bg-warning">Pengadaan</span>
                                                    @break
                                                @case('pelaksanaan')
                                                    <span class="badge bg-success">Pelaksanaan</span>
                                                    @break
                                                @case('laporan')
                                                    <span class="badge bg-secondary">Laporan</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-dark">{{ $item['jenis_monitoring'] }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($item['status_monitoring'])
                                                @case('Belum Dimulai')
                                                    <span class="badge bg-danger">Belum Dimulai</span>
                                                    @break
                                                @case('Sedang Berjalan')
                                                    <span class="badge bg-warning">Sedang Berjalan</span>
                                                    @break
                                                @case('Selesai')
                                                    <span class="badge bg-success">Selesai</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-dark">{{ $item['status_monitoring'] }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ number_format($item['program'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($item['realisasi'], 0, ',', '.') }}</td>
                                        <td>{{ $item['pic'] }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item['tanggal_mulai'])->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($item['tanggal_selesai'])->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('pages.keuangan.monev-keuangan.show', $item['id']) }}" class="btn btn-sm btn-info me-2">Detail</a>
                                                <a href="{{ route('pages.keuangan.monev-keuangan.edit', $item['id']) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                                                <form action="{{ route('pages.keuangan.monev-keuangan.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
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
            $('#tabelMonevKeuangan').DataTable();
        });
    </script>
    @endpush
</x-app-layout>