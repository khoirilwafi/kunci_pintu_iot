<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>white board</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <style>
        img {
            max-height: 100%;
        }
    </style>
</head>
<body>

    {{-- <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Profil Anda
            </div>
            <div class="card-body">
                <div class="row bg-success" style="max-height: 40vh">
                    <div class="col-5 bg-primary">
                        <div class="h-100">
                            <img src="{{ asset('/assets/img/image.png') }}" alt="foto">
                        </div>
                    </div>
                    <div class="col-7 d-flex" style="text-align:justify">
                        <div class="text-column">
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Earum, minima pariatur iste beatae libero at repudiandae excepturi odio, quis maxime totam quos debitis nobis ad aspernatur provident ipsum voluptas. Iusto doloribus vitae ad enim blanditiis maxime illum distinctio eius natus tempore sed, odit unde molestias corporis assumenda hic, consectetur debitis!<br>
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repellat voluptatibus neque, assumenda quibusdam facilis adipisci ipsum itaque, iusto magnam nulla ex eveniet magni asperiores illum minima maxime, porro quas quod! Quibusdam id, officiis necessitatibus, tempora ducimus quidem odit ipsum quisquam doloremque molestiae voluptate iste aliquam distinctio nesciunt. Libero, placeat autem.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}

    <div class="row" style="max-height: 24px;" >
        <div class="col-6">
            <div class="card h-100">
              <img src="https://via.placeholder.com/350x150" alt="" class="img-fluid" >
            </div>
        </div>
       <div class="col-6">
        <div class="card">
          <div>text goes here</div>
        </div>
     </div>
    </div><!--end row-->

    <script src="{{ asset('assets/jquery/jquery-3.6.1.min.js') }}"></script>
	<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
