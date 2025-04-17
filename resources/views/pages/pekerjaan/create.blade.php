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
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="nama_pekerjaan" class="form-label fw-bold">Nama Pekerjaan</label>
                                            <input type="text" id="nama_pekerjaan" class="form-control @error('nama_pekerjaan') is-invalid @enderror" 
                                                name="nama_pekerjaan" value="{{ old('nama_pekerjaan') }}" placeholder="Masukkan nama pekerjaan" required>
                                            @error('nama_pekerjaan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="volume" class="form-label fw-bold">Volume</label>
                                            <input type="number" id="volume" class="form-control @error('volume') is-invalid @enderror" 
                                                name="volume" value="{{ old('volume') }}" placeholder="Masukkan volume" step="any" required>
                                            @error('volume')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="satuan" class="form-label fw-bold">Satuan</label>
                                            <input type="text" id="satuan" class="form-control @error('satuan') is-invalid @enderror" 
                                                name="satuan" value="{{ old('satuan') }}" placeholder="Masukkan satuan" required>
                                            @error('satuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="sumber_keterangan" class="form-label fw-bold">Sumber Keterangan</label>
                                            <input type="text" id="sumber_keterangan" class="form-control @error('sumber_keterangan') is-invalid @enderror" 
                                                name="sumber_keterangan" value="{{ old('sumber_keterangan') }}" placeholder="Masukkan sumber keterangan" required>
                                            @error('sumber_keterangan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="sub_unit" class="form-label fw-bold">Sub Unit</label>
                                            <input type="text" id="sub_unit" class="form-control @error('sub_unit') is-invalid @enderror" 
                                                name="sub_unit" value="{{ old('sub_unit') }}" placeholder="Masukkan sub unit" required>
                                            @error('sub_unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="jenis_op" class="form-label fw-bold">Jenis OP</label>
                                            <select id="jenis_op" class="form-select @error('jenis_op') is-invalid @enderror" 
                                                name="jenis_op" required>
                                                <option value="" selected disabled>Pilih jenis OP</option>
                                                <option value="Investasi" {{ old('jenis_op') == 'Investasi' ? 'selected' : '' }}>Investasi</option>
                                                <option value="Operasional" {{ old('jenis_op') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                            </select>
                                            @error('jenis_op')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="nilai_paket_pekerjaan" class="form-label fw-bold">Nilai Paket Pekerjaan</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" id="nilai_paket_pekerjaan" class="form-control @error('nilai_paket_pekerjaan') is-invalid @enderror" 
                                                    name="nilai_paket_pekerjaan" value="{{ old('nilai_paket_pekerjaan') }}" placeholder="Masukkan nilai paket" required>
                                            </div>
                                            @error('nilai_paket_pekerjaan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="jadwal_mulai" class="form-label fw-bold">Jadwal Mulai</label>
                                            <input type="date" id="jadwal_mulai" class="form-control @error('jadwal_mulai') is-invalid @enderror" 
                                                name="jadwal_mulai" value="{{ old('jadwal_mulai') }}" required>
                                            @error('jadwal_mulai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="jadwal_selesai" class="form-label fw-bold">Jadwal Selesai</label>
                                            <input type="date" id="jadwal_selesai" class="form-control @error('jadwal_selesai') is-invalid @enderror" 
                                                name="jadwal_selesai" value="{{ old('jadwal_selesai') }}" required>
                                            @error('jadwal_selesai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="status_proses" class="form-label fw-bold">Status Proses</label>
                                            <select id="status_proses" class="form-select @error('status_proses') is-invalid @enderror" 
                                                name="status_proses" required>
                                                <option value="" selected disabled>Pilih status proses</option>
                                                <option value="Belum Dimulai" {{ old('status_proses') == 'Belum Dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                                <option value="Dalam Proses" {{ old('status_proses') == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                                                <option value="Selesai" {{ old('status_proses') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                            @error('status_proses')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="status_gr" class="form-label fw-bold">Status GR</label>
                                            <select id="status_gr" class="form-select @error('status_gr') is-invalid @enderror" 
                                                name="status_gr" required>
                                                <option value="" selected disabled>Pilih status GR</option>
                                                <option value="Belum GR" {{ old('status_gr') == 'Belum GR' ? 'selected' : '' }}>Belum GR</option>
                                                <option value="Sudah GR" {{ old('status_gr') == 'Sudah GR' ? 'selected' : '' }}>Sudah GR</option>
                                            </select>
                                            @error('status_gr')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="jenis_investasi" class="form-label fw-bold">Jenis Investasi</label>
                                            <input type="text" id="jenis_investasi" class="form-control @error('jenis_investasi') is-invalid @enderror" 
                                                name="jenis_investasi" value="{{ old('jenis_investasi') }}" placeholder="Masukkan jenis investasi" required>
                                            @error('jenis_investasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="div" class="form-label fw-bold">Divisi</label>
                                            <input type="text" id="div" class="form-control @error('div') is-invalid @enderror" 
                                                name="div" value="{{ old('div') }}" placeholder="Masukkan divisi" required>
                                            @error('div')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="nilai_item_investasi" class="form-label fw-bold">Nilai Item Investasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" id="nilai_item_investasi" class="form-control @error('nilai_item_investasi') is-invalid @enderror" 
                                                    name="nilai_item_investasi" value="{{ old('nilai_item_investasi') }}" placeholder="Masukkan nilai item investasi" required>
                                            </div>
                                            @error('nilai_item_investasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                            <label for="pbj" class="form-label fw-bold">PBJ</label>
                                            <input type="text" id="pbj" class="form-control @error('pbj') is-invalid @enderror" 
                                                name="pbj" value="{{ old('pbj') }}" placeholder="Masukkan PBJ" required>
                                            @error('pbj')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>                                
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
