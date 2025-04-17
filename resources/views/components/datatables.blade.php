{{-- filepath: d:\project_magang2\Si-Monka\resources\views\components\datatables.blade.php --}}
@props([
    'id' => 'datatable',
    'url' => '',
    'columns' => [],
    'aksi' => [],
    'filter' => [],
    'layoutTopEnd' => false
])

<div>
    @if($filter)
        <div class="mb-3">
            <div class="row">
                @foreach($filter as $filterItem)
                    <div class="col-md-3">
                        <label for="filter-{{ $filterItem['title'] }}" class="form-label">{{ $filterItem['title'] }}</label>
                        <select id="filter-{{ $filterItem['title'] }}" class="form-select filter-select">
                            <option value="">Semua</option>
                            @foreach($filterItem['data'] as $option)
                                <option value="{{ $option['key'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <table class="table table-striped table-bordered" id="{{ $id }}">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column['label'] }}</th>
                @endforeach
                @if($aksi)
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            {{-- Data will be populated via AJAX --}}
        </tbody>
    </table>
</div>

@pushOnce('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endPushOnce

@pushOnce('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        const table = $('#{{ $id }}').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ $url }}',
                data: function (d) {
                    $('.filter-select').each(function () {
                        const filterKey = $(this).attr('id').replace('filter-', '');
                        d[filterKey] = $(this).val();
                    });
                }
            },
            columns: [
                @foreach($columns as $column)
                    {
                        data: '{{ $column['key'] }}',
                        className: '{{ $column['style'] ?? '' }}',
                        render: function (data, type, row) {
                            @if(isset($column['customStyle']))
                                const styles = @json($column['customStyle']);
                                return `<span class="${styles[data] || ''}">${data}</span>`;
                            @else
                                return data;
                            @endif
                        }
                    },
                @endforeach
                @if($aksi)
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let actionButtons = '';
                            @if($aksi['detail'] ?? false)
                                actionButtons += `<a href="/${row.id}" class="btn btn-sm btn-info me-1">Detail</a>`;
                            @endif
                            @if($aksi['edit'] ?? false)
                                actionButtons += `<a href="/${row.id}/edit" class="btn btn-sm btn-warning me-1">Edit</a>`;
                            @endif
                            @if($aksi['hapus'] ?? false)
                                actionButtons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Hapus</button>`;
                            @endif
                            return actionButtons;
                        }
                    }
                @endif
            ]
        });

        $('.filter-select').on('change', function () {
            table.ajax.reload();
        });

        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: `/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        table.ajax.reload();
                        alert(response.message || 'Data berhasil dihapus.');
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON.message || 'Terjadi kesalahan.');
                    }
                });
            }
        });
    });
</script>
@endPushOnce