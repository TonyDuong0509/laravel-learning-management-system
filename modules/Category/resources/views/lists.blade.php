@extends('layouts.backend')
@section('content')
    <p><a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm mới</a></p>
    @if (session('msg'))
        <div class="alert alert-success">{{ session('msg') }}</div>
    @endif
    <table id="datatable" class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Link</th>
                <th>Thời gian</th>
                <th>Sửa</th>
                <th>Xoá</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Tên</th>
                <th>Link</th>
                <th>Thời gian</th>
                <th>Sửa</th>
                <th>Xoá</th>
            </tr>
        </tfoot>
    </table>
    @include('parts.backend.delete')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#datatable").DataTable({
                ajax: "{{ route('admin.categories.data') }}",
                processing: true,
                serverSide: true,
                "columns": [{
                        "data": "name"
                    },
                    {
                        "data": "link"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "edit"
                    },
                    {
                        "data": "delete"
                    },
                ]
            });
        });
    </script>
@endpush
