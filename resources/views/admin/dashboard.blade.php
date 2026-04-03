@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Welcome back, {{ Auth::user()->name }}! 👋</h1>
    <p>Here's what's happening with your platform today.</p>
</div>

<div class="card">
    <p>Dashboard content coming soon...</p>
</div>
@endsection