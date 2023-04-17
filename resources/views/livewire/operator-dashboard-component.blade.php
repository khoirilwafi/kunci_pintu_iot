<div>
    <div class="card">
        <div class="card-body">
            <h5 class="fs-3">Selamat Datang</h5>
            <p class="card-text">Sistem Penguncian Gedung Berbasis IoT merupakan sebuah sistem penguncian terintegrasi yang bertujuan untuk meningkatkan efektifitas kinerja perangkat penguncian serta meningkatkan keamanan dengan menggunakan pengaturan akses pengguna.</p>
            <div class="mt-3 d-flex">
                Koneksi :
                <div id="connection_status" class="ms-1" style="color: {{ $connection_color }}">{{ $connection_status }}</div>
            </div>
        </div>
    </div>
    <div class="row mt-4 mb-4">
        <div class="col-4">
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
        <div class="col-4">
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
        <div class="col-4">
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
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header text-center ">
                    Jadwal Hari ini
                </div>
                <div class="card-body fs-6">
                    @if (sizeof($jadwal_hari_ini) == 0)
                        <div class="text-center">-- belum ada jadwal --</div>
                    @else
                        @foreach ($jadwal_hari_ini as $jadwal)
                            @if ($jadwal->status != 'done')
                                <div class="bg-dark p-3 rounded d-flex mb-3">
                                    <div>{{ $jadwal->name }}</div>
                                    <div class="ms-auto {{ $jadwal->status == 'running' ? 'text-warning' : 'text-white' }}">
                                        {{ $jadwal->time_begin }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header text-center">
                    Jadwal Selesai
                </div>
                <div class="card-body fs-6">
                    @foreach ($jadwal_hari_ini as $jadwal)
                        @if ($jadwal->status == 'done')
                            <div class="bg-dark p-3 rounded d-flex mb-3">
                                <div>{{ $jadwal->name }}</div>
                                <div class="ms-auto">
                                    {{ $jadwal->time_begin }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom_script')
    @vite('resources/js/door-socket.js');
    <script>
        window.office = @json($office_id);
        window.connection_status = document.getElementById("connection_status");
    </script>
@endpush
