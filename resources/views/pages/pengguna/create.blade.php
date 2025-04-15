<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pengguna</h3>
                <p class="text-subtitle text-muted">Formulir untuk menambahkan pengguna baru ke sistem</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{-- route('pages.pengguna.index') --}}">Pengguna</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{-- route('pengguna.store') --}}" method="POST" class="form" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                        name="password" placeholder="Masukkan password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" class="form-control" 
                                        name="password_confirmation" placeholder="Konfirmasi password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group position-relative">
                                <label for="avatar" class="form-label fw-bold">Avatar</label>
                                <div class="dropzone-wrapper @error('avatar') is-invalid @enderror">
                                    <div class="dropzone-desc">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <p>Pilih foto atau seret file ke sini</p>
                                    </div>
                                    <input type="file" id="avatar" class="dropzone" name="avatar" accept="image/*">
                                </div>
                                <div class="image-preview mt-3" id="imagePreview">
                                    <img src="" alt="Preview Avatar" class="img-thumbnail d-none" id="previewImg">
                                </div>
                                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Upload foto profil pengguna (opsional)</small>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <div>
                                <button type="reset" class="btn btn-light-secondary me-1">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <style>
        .card {
            border-radius: 10px;
            border: none;
            overflow: hidden;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-label {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control {
            padding: 0.6rem 0.75rem;
            border: 1px solid #dce7f1;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
        
        .dropzone-wrapper {
            border: 2px dashed #ccc;
            border-radius: 8px;
            position: relative;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .dropzone-wrapper:hover, .dropzone-wrapper.dragover {
            background-color: #f0f8ff;
            border-color: #007bff;
        }
        
        .dropzone-desc {
            text-align: center;
            color: #6c757d;
        }
        
        .dropzone-desc i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #6c757d;
        }
        
        .dropzone {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            top: 0;
            left: 0;
        }
        
        .image-preview img {
            max-height: 150px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 5px;
        }
        
        .btn-primary {
            background-color: #435ebe;
            border-color: #435ebe;
        }
        
        .btn-primary:hover {
            background-color: #3950a2;
            border-color: #3950a2;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview functionality
            const dropzone = document.querySelector('.dropzone');
            const wrapper = document.querySelector('.dropzone-wrapper');
            const desc = document.querySelector('.dropzone-desc');
            const previewImg = document.getElementById('previewImg');
            
            dropzone.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    desc.innerHTML = `<i class="bi bi-file-earmark-check"></i><p>${fileName}</p>`;
                    
                    // Preview image
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.setAttribute('src', e.target.result);
                        previewImg.classList.remove('d-none');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            ['dragover', 'dragenter'].forEach(event => {
                wrapper.addEventListener(event, function(e) {
                    e.preventDefault();
                    wrapper.classList.add('dragover');
                });
            });
            
            ['dragleave', 'dragend', 'drop'].forEach(event => {
                wrapper.addEventListener(event, function(e) {
                    e.preventDefault();
                    wrapper.classList.remove('dragover');
                });
            });
            
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });
        });
    </script>
</x-app-layout>
