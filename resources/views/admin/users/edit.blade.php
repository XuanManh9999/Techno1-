@extends('layouts.admin')

@section('title', 'Sửa người dùng')

@section('content')
<script>
    window.onload = function() {
        window.location.href = "{{ route('admin.users.index') }}";
    }
</script>
@endsection

