@extends('layouts.backend')
@section('content')
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Tên</label>
                    <input type="text" id="generateSlug" name="name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Tên..."
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Slug</label>
                    <input type="text" id="slug" name="slug"
                        class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" placeholder="Slug..."
                        value="{{ old('slug') }}">
                    @error('slug')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Cha</label>
                    <select name="parent_id" id=""
                        class="form-select {{ $errors->has('parent_id') ? 'is-invalid' : '' }}">
                        <option value="0">Không</option>
                        {{ getCategories($categories, old('parent_id')) }}
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Huỷ</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let slug;
        let title
        document.getElementById('generateSlug').addEventListener('keyup', function() {
            title = this.value;

            slug = title.toLowerCase()
                .normalize("NFD").replace(/[\u0300-\u036f]/g, '') // Remove accents
                .replace(/đ/g, 'd').replace(/Đ/g, 'd') // Convert đ to d
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-'); // Remove multiple hyphens

            return document.getElementById('slug').value = slug;
        });
    </script>
@endpush
