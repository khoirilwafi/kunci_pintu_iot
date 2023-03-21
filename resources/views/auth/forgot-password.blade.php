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
						@if (session()->has('status'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session()->get('status') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif
						<form action="{{ url('/forgot-password') }}" method="post" id="forgot-form">
							@csrf
							<div class="mb-4">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" autofocus required>
								@error('email')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<button class="btn btn-primary w-100 btn-submit" type="submit">
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
								Kirim Link Reset Password
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
			$("#forgot-form").submit(function() {
				$(".spinner-border").removeClass("d-none");
				$(".btn-submit").attr("disabled", true);
			});
		});
	</script>
@endsection
