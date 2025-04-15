<x-app-layout>
    <x-slot name="header">
        <div class="row">
            
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengguna</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-data"><a href="{{ route('dashboard') }}">Dashboard&nbsp</a></li>
                        <li class="breadcrumb-data active" aria-current="page"> - Pengguna</li>
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
                        <h4>Daftar Pengguna</h4>
                        <a href="{{route('pages.pengguna.create')}}" class="btn btn-primary">Tambah Pengguna</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="tabelPengguna">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    @foreach($data as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td> <!-- Nomor urut -->
                                        <td>{{ $data['name'] }}</td>
                                        <td>{{ $data['email'] }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('pages.pengguna.show', $data->id ?? $data['id'])}}" class="btn btn-sm btn-info me-2">Detail</a>
                                                <a href="{{ route('pages.pengguna.edit', $data->id ?? $data['id']) }}" class="btn btn-sm btn-primary me-2">Edit</a>
                                                <form action="{{ route('pages.pengguna.destroy', $data->id ?? $data['id']) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
            $('#tabelPengguna').DataTable();
        });
    </script>
    @endpush
</x-app-layout>