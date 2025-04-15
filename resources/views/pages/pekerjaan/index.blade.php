<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pekerjaan</h3>
                <p class="text-subtitle text-muted">Halaman kelola data pekerjaan sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pekerjaan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Daftar Pekerjaan</h4>
                                <a href="{{ route('pages.pekerjaan.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Pekerjaan
                                </a>
                            </div>
                        </div>
                        <div class="card-body">                            
                            <div class="table-responsive">
                                <table class="table table-striped" id="myTable">
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
                                        @forelse($data as $index => $pekerjaan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pekerjaan['nama_pekerjaan'] }}</td>
                                            <td>{{ $pekerjaan['sub_unit'] }}</td>
                                            <td>{{ $pekerjaan['jenis_op'] }}</td>
                                            <td class="text-right">{{ number_format($pekerjaan['nilai_paket_pekerjaan'], 0, ',', '.') }}</td>
                                            <td>{{ date('d-m-Y', strtotime($pekerjaan['jadwal_mulai'])) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($pekerjaan['jadwal_selesai'])) }}</td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'Belum Dimulai' => 'badge-secondary',
                                                        'Dalam Proses' => 'badge-primary',
                                                        'Selesai' => 'badge-success'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $statusClass[$pekerjaan['status_proses']] ?? 'badge-secondary' }}">
                                                    {{ $pekerjaan['status_proses'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $pekerjaan['status_gr'] == 'Sudah GR' ? 'badge-success' : 'badge-warning' }}">
                                                    {{ $pekerjaan['status_gr'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('pages.pekerjaan.show', $pekerjaan['id']) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pages.pekerjaan.edit', $pekerjaan['id']) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                            data-toggle="modal" data-target="#deleteModal" 
                                                            data-id="{{ $pekerjaan['id'] }}"
                                                            data-name="{{ $pekerjaan['nama_pekerjaan'] }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data pekerjaan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus pekerjaan <span id="delete-item-name"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <form id="delete-form" action="" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Handle Delete Button Click
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const deleteUrl = "{{ route('pages.pekerjaan.destroy', ':id') }}".replace(':id', id);
                
                $('#delete-item-name').text(name);
                $('#delete-form').attr('action', deleteUrl);
            });
        });
    </script>
    @endpush
</x-app-layout>