@extends('layouts.backend')
@section('content')
    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Tên</label>
                    <input type="text" name="name" id="generateSlug"
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
                    <input type="text" name="slug" id="slug"
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
                    <label for="">Giảng viên</label>
                    <select name="teacher_id" id=""
                        class="form-select {{ $errors->has('teacher_id') ? 'is-invalid' : '' }}">
                        <option value="0">Chọn giảng viên</option>
                        <option value="1">Francis Duong</option>
                    </select>
                    @error('teacher_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Mã khoá học</label>
                    <input type="text" name="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                        placeholder="Mã khoá học..." value="{{ old('code') }}">
                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Giá khoá học</label>
                    <input type="number" name="price"
                        class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" placeholder="Giá khoá học..."
                        value="{{ old('price') }}">
                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Giá khuyến mãi</label>
                    <input type="number" name="sale_price"
                        class="form-control {{ $errors->has('sale_price') ? 'is-invalid' : '' }}"
                        placeholder="Giá khuyến mãi..." value="{{ old('sale_price') }}">
                    @error('sale_price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Tài liệu đính kèm</label>
                    <select name="is_document" id=""
                        class="form-select {{ $errors->has('is_document') ? 'is-invalid' : '' }}">
                        <option value="0" {{ old('is_document') == 0 ? 'selected' : '' }}>Không</option>
                        <option value="1" {{ old('is_document') == 0 ? 'selected' : '' }}>Có</option>
                    </select>
                    @error('is_document')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3">
                    <label for="">Trạng thái</label>
                    <select name="status" id=""
                        class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <option value="0" {{ old('status') == 0 ? 'selected' : false }}>Chưa ra mắt</option>
                        <option value="1" {{ old('status') == 0 ? 'selected' : false }}>Đã ra mắt</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="">Hỗ trợ</label>
                    <textarea name="supports" class="form-control {{ $errors->has('supports') ? 'is-invalid' : '' }}"
                        placeholder="Hỗ trợ...">{!! old('detail') !!}</textarea>
                    @error('supports')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="">Nội dung</label>
                    <textarea name="detail" id="ckeditor" class="form-control">{!! old('detail') !!}</textarea>
                    @error('detail')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <label for="">Chuyên mục</label>
                    <div style="max-height: 250px; overflow: auto">
                        {{ getCategoriesCheckbox($categories, old('categories')) }}
                    </div>
                    @error('categories')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-3">
                    <div class="row align-items-end">
                        <div class="col-7">
                            <label>Ảnh đại diện</label>
                            <input type="file" name="thumbnail" accept="image/*" onchange="loadFile(event)"
                                class="form-control {{ $errors->has('thumbnail') ? 'is-invalid' : '' }}"
                                value="{{ old('thumbnail') }}">
                            @error('thumbnail')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-3">
                            <img width="200" height="150" style="object-fit: contain" id="preview-image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-danger">Huỷ</a>
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

    <script>
        var loadFile = function(event) {
            var output = document.getElementById('preview-image');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
@endpush
