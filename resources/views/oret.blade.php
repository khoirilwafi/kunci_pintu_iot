<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title> {{ config('app.name') }} </title>

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>

<body>

    <div id="poster" style="width:595px; height:842px;" class="p-3 mx-auto">
        <div style="overflow:hidden;" class="poster-content d-flex flex-column align-items-center rounded">
            <div class="text-center mt-4 p-3 mb-4">
                <div class="fs-2 fw-bold">Scann di sini</div>
                <div class="lh-sm mt-2">Pintu ini dilindungi oleh SmartLock&#8482;</div>
                <div class="lh-sm">Pindai QR code dibawah untuk mendapatkan akses ruangan</div>
            </div>

            <div class="mb-4 p-3 bg-white rounded">
                {!! QrCode::size(180)->generate("https://smartdoorlock.my.id/door/kjinakdkajdiknnoashbkjkjnajhkbcsjd") !!}
            </div>

            <div class="text-center py-3 mb-4">
                <div class="fs-4 fw-bold">Ruang Arsip Tugas Akhir</div>
                <div>Gedung Teknik Elektro</div>
            </div>

            <div class="bg-primary p-3 text-white w-100 text-center">
                <div class="fw-bold">CARA SCANN QR CODE</div>
                <hr>
                <div class="row mb-2">
                    <div class="col-4">
                        <div class="d-flex">
                            <div class="fs-5 me-2"><i class="bi bi-1-square"></i></div>
                            <div class="text-start lh-sm"><small>Download dan install aplikasi SmartLock melalui QR code diatas</small></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex">
                            <div class="fs-5 me-2"><i class="bi bi-2-square"></i></div>
                            <div class="text-start lh-sm"><small>Buka aplikasi SmartLock kemudian pilih menu Quick Scann</small></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex">
                            <div class="fs-5 me-2"><i class="bi bi-3-square"></i></div>
                            <div class="text-start lh-sm"><small>Pindai QR code diatas, jika berhasil maka kunci pintu akan terbuka</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid mt-5">
        <button id="button_poster" class="btn btn-primary">Unduh Poster</button>
    </div>

	<script src="{{ asset('assets/jquery/jquery-3.6.1.min.js') }}"></script>
	<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- @vite('resources/js/poster.js'); --}}

    <script>
        window.poster = document.getElementById("poster");
        window.btn_download = document.getElementById("button_poster");
    </script>

</body>
</html>
