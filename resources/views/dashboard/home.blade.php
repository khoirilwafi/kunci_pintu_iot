@extends('template.dashboard')

@section('content')
    @can('operator')
        @livewire('operator-dashboard-component')
    @endcan
    @can('moderator')
        <div class="text-center">
            <div class="fs-3">Selamat Datang</div>
            <small>dashboard.html</small>
        </div>
    @endcan
@endsection
