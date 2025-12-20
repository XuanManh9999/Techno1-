@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
<script>
    window.onload = function() {
        window.location.href = "{{ route('admin.categories.index') }}";
    }
</script>
@endsection

