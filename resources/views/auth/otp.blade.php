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
						@if (session()->has('failed'))
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ session()->get('failed') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif
						<form action="{{ url('/email-verification') }}" method="post" id="forgot-form">
							@csrf
                            <input type="hidden" name="id" value="{{ $id }}">
							<div class="mb-4">
								<label class="form-label">Kode OTP</label>
								<input type="number" class="form-control @error('otp') is-invalid @enderror" name="otp" autofocus required>
								@error('otp')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<button class="btn btn-primary w-100 btn-submit" type="submit">
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
								Verifikasi
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
