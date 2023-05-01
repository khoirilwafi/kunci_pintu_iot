<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title> {{ config('app.name') }} </title>

	<link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

</head>

<body>

	@yield('content')

	<div class="fixed-bottom text-center mb-2 signature">
		<small>&#169; 2023 Tugas Akhir Universitas Diponegoro</small>
	</div>

	<script src="{{ asset('assets/jquery/jquery-3.6.1.min.js') }}"></script>
	<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

	@yield('custom_script')

</body>

</html>
