<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Keuangan</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-data"><a href="{{ route('dashboard') }}">Dashboard&nbsp</a></li>
                        <li class="breadcrumb-data"><a href="#">Keuangan&nbsp</a></li>
                        <li class="breadcrumb-data active" aria-current="page"> - Data Keuangan</li>
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
                        <h4>Daftar Data Keuangan</h4>
                        <a href="{{route('pages.keuangan.data-keuangan.create')}}" class="btn btn-primary">Tambah Data Keuangan</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabelDataKeuangan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pekerjaan</th>
                                        <th>RKAP (Rp)</th>
                                        <th>RKAPT (Rp)</th>
                                        <th>PjPSDA (Rp)</th>
                                        <th>RAB (Rp)</th>
                                        <th>Nomor IO</th>
                                        <th>Kontrak (Rp)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td> <!-- Nomor urut -->
                                        <td>{{ $item['pekerjaan']['nama_pekerjaan'] ?? 'N/A' }}</td>
                                        <td>{{ number_format($item['rkap'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($item['rkapt'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($item['pjpsda'], 0, ',', '.') }}</td>
                                        <td>{{ number_format($item['rab'], 0, ',', '.') }}</td>
                                        <td>{{ $item['nomor_io'] }}</td>
                                        <td>{{ number_format($item['real_kontrak'], 0, ',', '.') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('pages.keuangan.data-keuangan.show', $item['id']) }}" class="btn btn-sm btn-info me-2">Detail</a>
                                                <a href="{{ route('pages.keuangan.data-keuangan.edit', $item['id']) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                                                <form action="{{ route('pages.keuangan.data-keuangan.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
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
            $('#tabelDataKeuangan').DataTable({
                scrollX: true
            });
        });
    </script>
    @endpush
</x-app-layout>