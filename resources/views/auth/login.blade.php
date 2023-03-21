@extends('template.auth')

@section('content')
	<div class="container" style="height:80vh">
		<div class="h-100 d-flex align-items-center justify-content-center">
			<div class="col-11 col-md-4">
				<div class="d-flex fs-2 justify-content-center">
					<div class="fw-bold">Smart</div>Lock&#8482;
				</div>
				<div class="mb-4 text-center">Sistem Keamanan Kunci Gedung Berbasis IoT</div>
				<div class="card w-100 shadow-sm">
					<div class="card-body">
						@if (session()->has('login_failed'))
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ session()->get('login_failed') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif
						@if (session()->has('status'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session()->get('status') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif
						<form action="{{ url('/login') }}" method="post" id="login-form">
							@csrf
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
									value="{{ old('email') }}" autofocus required>
								@error('email')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class="mb-3">
								<div class="d-flex">
									<label for="password" class="form-label">Password</label>
									<a href="{{ url('/forgot-password') }}" class="ms-auto">Lupa Password</a>
								</div>
								<input type="password" class="form-control" id="password" name="password" required>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
								<label class="form-check-label" for="remember">
									Ingat Saya
								</label>
							</div>
							<button class="btn btn-primary w-100 mt-4 btn-submit" type="submit">
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
								Login
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('custom_script')
	<script>
		$(document).ready(function() {
			$("#login-form").submit(function() {
				$(".spinner-border").removeClass("d-none");
				$(".btn-submit").attr("disabled", true);
			});
		});
	</script>
@endsection
