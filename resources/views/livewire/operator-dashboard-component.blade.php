<div>
    {{-- welcome card --}}
    <div id="welcome-text" class="card mb-2 d-none">
        <div class="card-body">
            <h5 class="fs-3">Selamat Datang</h5>
            <p class="card-text"><small>Sistem Penguncian Gedung Berbasis IoT merupakan sebuah sistem penguncian terintegrasi yang bertujuan untuk meningkatkan efektifitas kinerja perangkat penguncian serta meningkatkan keamanan dengan menggunakan pengaturan akses pengguna.</small></p>
            <div class="mt-3 d-flex">
                Koneksi :
                <div id="connection_status" class="ms-1" style="color: {{ $connection_color }}">{{ $connection_status }}</div>
            </div>
        </div>
    </div>

    {{-- door status --}}
    <div class="row mt-lg-4 mt-2">
        <div class="col-lg-4 col-md-6 col-12 mb-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    Jumlah Pintu
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="bg-dark rounded fs-1 py-1 px-3">{{ $pintu }}</div>
                        <div class="ms-3"><small>Ruang yang sudah terpasang perangkat kunci pintu</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 mb-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    Pintu Online
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="bg-dark rounded fs-1 py-1 px-3">{{ $pintu_online }}</div>
                        <div class="ms-3"><small>Pintu yang terhubung ke server (perangkat sedang online)</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 mb-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    Pintu Terbuka
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="bg-dark rounded fs-1 py-1 px-3">{{ $pintu_terbuka }}</div>
                        <div class="ms-3"><small>Pintu dalam kondisi tidak terkunci (terbuka untuk publik)</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- door schedule --}}
    @if (count($jadwal_hari_ini) > 0)
        <div class="card">
            <div class="card-header">
                Jdawal Pintu
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="d-flex">
                            <div class="bg-dark rounded fs-1 py-1 px-3">{{ count($jadwal_hari_ini) }}</div>
                            <div class="ms-3"><small>Pintu terbuka secara otomatis sesuai jadwal yang telah ditentukan</small></div>
                        </div>
                    </div>
                    <div class="col-md-8 col-12 mt-md-0 mt-3">
                        <table id="lg-table" class="table bordered text-white d-none">
                            <thead>
                                <tr class="align-middle bg-secondary">
                                    <th style="padding-left: 20px">Nama</th>
                                    <th class="text-center">Waktu</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwal_hari_ini as $index => $jadwal)
                                    <tr class="align-middle" style="height:50px">
                                        <td style="padding-left: 20px">{{ $jadwal->name }}</td>
                                        <td class="text-center">{{ $jadwal->time_begin. ' sd '. $jadwal->time_end }}</td>
                                        <td class="text-center">{{ ucfirst($jadwal->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table id="sm-table" class="table bordered text-white d-none">
                            <thead>
                                <tr class="align-middle bg-secondary">
                                    <th style="padding-left: 20px">Nama</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwal_hari_ini as $index => $jadwal)
                                    <tr class="align-middle" style="height:50px">
                                        <td style="padding-left: 20px">{{ $jadwal->name }}</td>
                                        <td class="text-center">{{ ucfirst($jadwal->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-5"></div>

    {{-- alert modal --}}
    <div wire:ignore.self class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Peringatan
                </div>
                <div class="modal-body">
                    <div class="d-flex p-1">
                        <div class="text-warning" style="font-size:80px">
                            <i class="bi bi-exclamation-triangle ms-2"></i>
                        </div>
                        <div class="ms-4 me-2 d-flex align-items-center">
                            <div>
                                <div class="fs-5 mb-1">{{ $door_name }}</div>
                                <div style="text-align: justify">{{ $alert_message }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Keluar
                    </button>
                </div>
			</div>
		</div>
	</div>
</div>

@push('custom_script')
    @vite('resources/js/door-socket.js');
    <script type="text/javascript">
        window.office = @json($office_id);
        window.connection_status = document.getElementById("connection_status");

        window.addEventListener('load', function() {
            setTimeout(function() {
                let pintu = @json($pintu);
                let online = @json($pintu_online);

                if(pintu != online){
                    alert(pintu - online + ' Pintu Sedang Offline. \nKondisi pintu tidak bisa terpantau secara realtime.');
                }
            }, 1000);
        });

        $(document).ready(function () {
            if (window.innerWidth > window.innerHeight){
                $('#welcome-text').removeClass('d-none');
                $('#lg-table').removeClass('d-none');
            }
            else
            {
                $('#sm-table').removeClass('d-none');
            }
        });
    </script>
@endpush
