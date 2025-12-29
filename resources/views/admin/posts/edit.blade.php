@extends('layouts.admin')

@section('title', 'Sửa bài viết')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-file-text"></i> Sửa bài viết</h2>
        <p class="text-muted mb-0">Cập nhật thông tin bài viết</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $post->title) }}" required>
                @error('title')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug (URL)</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                       value="{{ old('slug', $post->slug) }}">
                @error('slug')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <small class="text-muted">Ví dụ: bai-viet-moi</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="excerpt" class="form-control" rows="3" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
                <small class="text-muted">Tối đa 500 ký tự</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Hình ảnh đại diện</label>
                @if($post->featured_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" 
                             class="img-thumbnail" style="max-width: 200px;">
                    </div>
                @endif
                <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" 
                       accept="image/*">
                @error('featured_image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" 
                          rows="15" required>{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ngày xuất bản</label>
                        <input type="datetime-local" name="published_at" 
                               class="form-control" 
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <div class="form-check">
                            <input type="hidden" name="is_published" value="0">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                                   {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Xuất bản
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Cập nhật
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
tinymce.init({
    selector: '#content',
    height: 500,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help | image | link | code',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px }',
    language: 'vi',
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.posts.upload-image") }}');
            
            // Add CSRF token
            var token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
            }
            
            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };
            
            xhr.onload = function () {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }
                
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }
                
                var json = JSON.parse(xhr.responseText);
                
                if (!json || typeof json.location != 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                
                resolve(json.location);
            };
            
            xhr.onerror = function () {
                reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };
            
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            
            xhr.send(formData);
        });
    }
});
</script>
@endpush
@endsection

