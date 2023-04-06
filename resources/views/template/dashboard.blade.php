<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title> {{ env('APP_NAME') }} </title>

	<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    @stack('custom_links')
    @livewireStyles()

</head>

<body>

	<div class="wrapper">

        {{-- sidebar --}}
		<div id="sidebar" class="d-flex flex-column active">
			<div class="logo mb-2 d-flex align-items-center justify-content-center text-white">
				<a class="d-flex fs-4 nav-link text-white" href="{{ url('dashboard') }}">
					<div class="fw-bold">Smart</div>Lock&#8482;
				</a>
			</div>
			<ul class="nav flex-column mb-5 p-3">
                @can('moderator')
                    <li class="sidebar-menu {{ request()->is('dashboard/offices') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/offices') }}" class="nav-link">
                            <i class="bi bi-building me-2"></i>Daftar Gedung
                        </a>
                    </li>
                    <hr class="sidebar-hr">
                    <li class="sidebar-menu {{ request()->is('dashboard/operators') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/operators') }}" class="nav-link">
                            <i class="bi bi-person-badge me-2"></i>Daftar Operator
                        </a>
                    </li>
                    <hr class="sidebar-hr">
                @endcan
                @can('operator')
                    <li class="sidebar-menu {{ request()->is('dashboard/doors') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/doors') }}" class="nav-link">
                            <i class="bi bi-door-open me-2"></i>Daftar Pintu
                        </a>
                    </li>
                    <hr class="sidebar-hr">
                    <li class="sidebar-menu {{ request()->is('dashboard/users') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/users') }}" class="nav-link">
                            <i class="bi bi-person-lock me-2"></i>Daftar Pengguna
                        </a>
                    </li>
                    <hr class="sidebar-hr">
                    <li class="sidebar-menu {{ request()->is('dashboard/schedules') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/schedules') }}" class="nav-link">
                            <i class="bi bi-clock-history me-2"></i>Penjadwalan
                        </a>
                    </li>
                    {{-- <hr class="sidebar-hr">
                    <li class="sidebar-menu {{ request()->is('dashboard/guests') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/guests') }}" class="nav-link">
                            <i class="bi bi-clipboard-check me-2"></i>Undangan
                        </a>
                    </li> --}}
                    {{-- <hr class="sidebar-hr">
                    <li class="sidebar-menu {{ request()->is('dashboard/inboxs') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/inboxs') }}" class="nav-link">
                            <i class="bi bi-envelope me-2"></i>Pesan Masuk
                        </a>
                    </li> --}}
                    <hr class="sidebar-hr">
                    <li class="sidebar-menu {{ request()->is('dashboard/histories') ? 'high-light' : '' }}">
                        <a href="{{ url('/dashboard/histories') }}" class="nav-link">
                            <i class="bi bi-card-checklist me-2"></i>Riwayat Akses
                        </a>
                    </li>
                    <hr class="sidebar-hr">
                @endcan
				<li class="sidebar-menu {{ request()->is('dashboard/my-account') ? 'high-light' : '' }}">
					<a href="{{ url('/dashboard/my-account') }}" class="nav-link">
						<i class="bi bi-person-gear me-2"></i>Pengaturan Akun
					</a>
				</li>
			</ul>
			<div class="nav flex-column mt-auto p-3">
				<li class="sidebar-menu text-center">
					<a href="{{ url('/logout') }}" class="nav-link">
						<i class="bi bi-power me-1"></i>
						Logout
					</a>
				</li>
			</div>
		</div>

		<div id="content">

            {{-- topbar --}}
			<div class="d-flex content-header bg-dark text-white align-items-center px-4">
				<div>
					@if ($user_role == 'moderator')
						<div class="bg-secondary rounded px-3 py-1">Moderator</div>
					@else
						<div class=""><i class="bi bi-building me-2"></i>{{ $user_office }}</div>
					@endif
				</div>

				<div class="d-flex h-100 align-items-center ms-auto">
					<div class="text-end me-2">
						<div class="lh-1"><small id="username">{{ $user_name }}</small></div>
						<div class="lh-1"><small>{{ ucfirst($user_role) }}</small></div>
					</div>
					<img src="{{ url("my-account/avatar/". $img_avatar) }}" alt="foto" class="avatar" id="avatar">
				</div>

			</div>

            {{-- main content --}}
            <div class="text-white content-wrapper">

                {{-- loading screen --}}
                <div class="" id="loading-screen">
                </div>

                {{-- content --}}
                <div class="p-3 p-md-4">
                    @yield('content')
                </div>

            </div>


		</div>


        {{-- sidebar overlay --}}
		<div id="overlay">
        </div>

	</div>

	<script src="{{ asset('assets/jquery/jquery-3.6.1.min.js') }}"></script>
	<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

	@livewireScripts()
    @stack('custom_script')

    <script type="text/javascript">
        window.addEventListener('modal_open', (event) => {
			$('#'+event.detail).modal('show');
		});

		window.addEventListener('modal_close', (event) => {
			$('#'+event.detail).modal('hide');
		});

        window.addEventListener('avatar_change', (event) => {
			$('#avatar').attr('src', "/my-account/avatar/" + event.detail);
		});

        window.addEventListener('name_change', (event) => {
			$('#username').html(event.detail);
		});
    </script>

</body>

</html>
