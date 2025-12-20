@extends('layouts.admin')

@section('title', 'Thêm thương hiệu')

@section('content')
<script>
    window.onload = function() {
        window.location.href = "{{ route('admin.brands.index') }}";
    }
</script>
@endsection

