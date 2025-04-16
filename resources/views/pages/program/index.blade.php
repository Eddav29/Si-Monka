<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Program</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-data"><a href="{{ route('dashboard') }}">Dashboard&nbsp</a></li>
                        <li class="breadcrumb-data active" aria-current="page"> - Program</li>
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
                        <h4>Daftar Program</h4>
                        <a href="{{route('pages.program.create')}}" class="btn btn-primary">Tambah Program</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabelProgram">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pekerjaan</th>
                                        <th>Pelaksanaan Program</th>
                                        <th>Status Program</th>
                                        <th>Realisasi Program</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td> <!-- Nomor urut -->
                                        <td>{{ $item['pekerjaan']['nama_pekerjaan'] ?? 'N/A' }}</td>
                                        <td>{{ $item['pelaksanaan_program'] }}</td>
                                        <td>{{ $item['status_program'] }}</td>
                                        <td>{{ $item['realisasi_program'] }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('pages.program.show', $item['id']) }}" class="btn btn-sm btn-info me-2">Detail</a>
                                                <a href="{{ route('pages.program.edit', $item['id']) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                                                <form action="{{ route('pages.program.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
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
            $('#tabelProgram').DataTable();
        });
    </script>
    @endpush
</x-app-layout>