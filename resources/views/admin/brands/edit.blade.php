@extends('layouts.admin')

@section('title', 'Sửa thương hiệu')

@section('content')
<script>
    window.onload = function() {
        window.location.href = "{{ route('admin.brands.index') }}";
    }
</script>
@endsection

