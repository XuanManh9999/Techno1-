@extends('layouts.admin')

@section('title', 'Thêm người dùng')

@section('content')
<script>
    window.onload = function() {
        window.location.href = "{{ route('admin.users.index') }}";
    }
</script>
@endsection

