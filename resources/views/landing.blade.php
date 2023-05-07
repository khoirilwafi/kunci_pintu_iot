<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ config('app.name') }} </title>

    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <style>
        @media screen and (max-width: 999px) {
            #apk_mobile {
                display: none !important;
            }

            #landing_text {
                text-align: center !important;
            }

            #landing_btn {
                display: grid !important;
                margin-bottom: 1rem !important;
                margin-left: 0px !important;
                margin-right: 0px !important;
            }
        }
    </style>

</head>
<body>

    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="row" style="width: 80vw">
            <div id="landing_text" class="col-lg-8 col-12">
                <h1 class="display-4"><strong>Smart</strong>Lock&#8482;</h1>
                <p class="lead">
                    Sistem keamanan kunci pintu gedung berbasis IoT untuk mengatur akses tiap pintu dengan cepat, aman dan efisien.
                    Menerapkan Level Access Management dan Realtime Monitoring menjadikan keamanan pintu terjamin dan dapat dipantau setiap saat, serta Remote Access dan Quick Access menjadikan penggunaan akses pintu semakin fleksibel dan efisien.
                </p>
                <p class="lead mt-5">
                    <a id="landing_btn" class="btn btn-primary me-2" href="{{ config('app.apk_url') }}" role="button"><i class="bi bi-google-play me-2"></i>Download APK</a>
                    <a id="landing_btn" class="btn btn-outline-primary" href="{{ url('/login') }}" role="button"><i class="bi bi-person-circle me-2"></i>Login Dashboard</a>
                </p>
            </div>
            <div id="apk_mobile" class="col-lg-4 col-11 d-flex">
                <img src="{{ asset('assets/img/mobile_app.png') }}" style="height:450px" class="ms-auto">
            </div>
        </div>
    </div>

</body>
</html>
