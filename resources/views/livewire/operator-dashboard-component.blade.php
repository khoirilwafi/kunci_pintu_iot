<div>
    {{-- welcome card --}}
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

    {{-- door status --}}
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

    {{-- door schedule --}}
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header text-center ">
                    Jadwal Hari Ini (Menuggu)
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
                    Jadwal Hari Ini (Selesai)
                </div>
                <div class="card-body fs-6">
                    {{ $this->count = false }}
                    @foreach ($jadwal_hari_ini as $jadwal)
                        @if ($jadwal->status == 'done')
                            <div class="bg-dark p-3 rounded d-flex mb-3">
                                <div>{{ $jadwal->name }}</div>
                                <div class="ms-auto">
                                    {{ $jadwal->time_begin }}
                                </div>
                                {{ $this->count = true}}
                            </div>
                        @endif
                    @endforeach
                    @if (!$this->count)
                        <div class="text-center">-- belum ada data --</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                                <div style="text-align: justify">Terbuka tanpa autentikasi yang sah. Mungkin terjadi penerobosan pada pintu tersebut.</div>
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

        let pintu = @json($pintu);
        let online = @json($pintu_online);

        if(pintu != online){
            alert(pintu - online + ' Pintu Sedang Offline. \nKondisi pintu tidak bisa terpantau secara realtime.');
        }
    </script>
@endpush
