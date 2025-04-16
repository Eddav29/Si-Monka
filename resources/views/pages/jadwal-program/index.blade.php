<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Jadwal Program</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-data"><a href="{{ route('dashboard') }}">Dashboard&nbsp</a></li>
                        <li class="breadcrumb-data active" aria-current="page"> - Jadwal Program</li>
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
                        <h4>Daftar Jadwal Program</h4>
                        <a href="{{route('pages.jadwal-program.create')}}" class="btn btn-primary">Tambah Jadwal Program</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabelJadwalProgram">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Program</th>
                                        <th>Pekerjaan</th>
                                        <th>Desain</th>
                                        <th>Verifikasi</th>
                                        <th>PBJ</th>
                                        <th>Pelaksanaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td> <!-- Nomor urut -->
                                        <td>{{ $item['program']['pelaksanaan_program'] ?? 'N/A' }}</td>
                                        <td>{{ $item['program']['pekerjaan']['nama_pekerjaan'] ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['desain'])->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['verifikasi'])->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['pbj'])->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['pelaksanaan'])->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('pages.jadwal-program.show', $item['id']) }}" class="btn btn-sm btn-info me-2">Detail</a>
                                                <a href="{{ route('pages.jadwal-program.edit', $item['id']) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                                                <form action="{{ route('pages.jadwal-program.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
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
            $('#tabelJadwalProgram').DataTable();
        });
    </script>
    @endpush
</x-app-layout>