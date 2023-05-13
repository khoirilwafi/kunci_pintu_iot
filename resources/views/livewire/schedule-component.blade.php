<div>
    {{-- notification --}}
    <div id="notification">
        @if (session()->has('insert_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('insert_success') }}</strong> berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('insert_failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('insert_failed') }}</strong> gagal ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('delete_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('delete_success') }}</strong> berhasil dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('delete_failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('delete_failed') }}</strong> gagal dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('update_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('update_success') }}</strong> berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('update_failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('update_failed') }}</strong> gagal diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    {{-- schedule table --}}
    @if ($schedule_table_visibility)
        <div class="card text-white mb-4">
            <div class="card-header d-flex">
                <div class="text-white">
                    Daftar Jadwal
                </div>
                <div class="ms-auto d-flex align-items-center">
                    <div id="connection_status" style="color: {{ $connection_color }}">{{ $connection_status }}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4 d-flex">
                    <div class="col-auto p-0">
                        <button wire:click="openModal('addScedule')" type="button" class="btn btn-sm btn-success bg-gradient">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search"
                            placeholder="Cari Jadwal ..." wire:model="search" autocomplete="off">
                    </div>
                </div>
                <table class="table text-white mb-4">
                    @if (sizeof($schedules) == 0)
                        <div class="text-center text-white p-3">
                            --- tidak ada data ---
                        </div>
                    @else
                        <thead>
                            <tr class="align-middle bg-secondary">
                                <th class="text-center" style="width: 60px">No</th>
                                <th>Nama</th>
                                <th class="text-center" style="width: 220px">Tanggal</th>
                                <th class="text-center" style="width: 190px">Waktu Harian</th>
                                <th class="text-center" style="width: 80px">Berulang</th>
                                <th class="text-center" style="width: 100px">Status</th>
                                <th class="text-center" style="width: 200px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $index => $schedule)
                                <tr class="align-middle" style="height: 60px">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $schedule->name }}</td>
                                    <td class="text-center">{{ $schedule->date_begin. ' sd '. $schedule->date_end }}</td>
                                    <td class="text-center">{{ $schedule->time_begin. ' sd '. $schedule->time_end }}</td>
                                    <td class="text-center">{{ ($schedule->is_repeating == 1) ? 'Ya' : 'Tidak' }}</td>
                                    <td class="text-center {{ $schedule->status == 'waiting' ? 'text-info' : 'text-warning' }}">{{ ucfirst($schedule->status) }}</td>
                                    <td class="text-center">
                                        <button wire:click="getSceduleDetail('{{ $schedule->id }}')" type="button" class="btn btn-sm btn-primary bg-gradient me-1">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat
                                        </button>
                                        <button wire:click="deleteConfirm('{{ $schedule->id }}')" type="button" class="btn btn-sm btn-danger bg-gradient">
                                            <i class="bi bi-trash me-1"></i>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    @endif

    {{-- schedule detail --}}
    @if ($schedule_detail_visibility)
        <button wire:click="showTable" type="button" class="btn btn-sm btn-success bg-gradient mb-4">
            <i class="bi bi-arrow-left-square me-1"></i>
            Kembali
        </button>
        <div class="card">
            <div class="card-header d-flex">
                <div class="text-white">
                    Detail Jadwal
                </div>
                <div class="ms-auto d-flex align-items-center">
                    <div id="connection_status" style="color: {{ $connection_color }}">{{ $connection_status }}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="p-2 mb-5 rounded border border-secondary d-flex">
                    <div class="fs-1 text-white ms-3"><i class="bi bi-clock-history"></i></div>
                    <table class="ms-3">
                        <tr>
                            <td class="px-2">Nama</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $insert_name }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Tanggal Mulai</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $insert_date_begin. ' sd '. $insert_date_end }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Waktu</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $insert_time_begin. ' sd '. $insert_time_end }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Status</td>
                            <td class="px-2">:</td>
                            <td class="px-2 {{ $insert_status == 'waiting' ? 'text-info' : 'text-warning' }}">{{ ucfirst($insert_status) }}</td>
                        </tr>
                        @if ($insert_is_repeat == 1)
                            <tr>
                                <td class="px-2">Berulang</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ $day_repeating }}</td>
                            </tr>
                        @endif
                    </table>
                    <div class="flex-grow-1 d-flex">
                        <div class="ms-auto">
                            @if($insert_status != 'running')
                                <button class="btn btn-sm btn-outline-primary ms-1" wire:click="edit"><div class="fs-6 text-white"><i class="bi bi-pencil-square"></i></div></button>
                            @else
                                <button class="btn btn-sm btn-outline-warning ms-1" wire:click="scheduleStop" wire:loading.attr='disabled'><div class="fs-6 text-white"><i class="bi bi-stop-circle"></i></div></button>
                            @endif
                        </div>
                    </div>
                </div>
                @if($insert_status != 'running')
                    <div class="d-flex mb-3">
                        <button class="btn btn-sm btn-primary" wire:click="openModal('addDoor')"><i class="bi bi-plus-circle me-1"></i>Tambah Pintu</button>
                    </div>
                @endif
                @if (sizeof($door_links) != 0)
                    <table class="table text-white">
                        <thead>
                            <tr class="align-middle bg-secondary">
                                <th class="text-center" style="width: 60px">No</th>
                                <th>Nama</th>
                                <th class="text-center" style="width: 210px">Device ID</th>
                                <th class="text-center" style="width: 90px">Status</th>
                                <th class="text-center" style="width: 150px">Penguncian</th>
                                @if ($insert_status == 'running')
                                    <th class="text-center" style="width: 120px">Override</th>
                                @else
                                    <th class="text-center" style="width: 120px">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($door_links as $index => $list)
                                <tr class="align-middle" style="height: 60px">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $list->door->name }}</td>
                                    <td class="text-center" style="font-family: monospace">
                                        @if ($list->door->device_id == null)
                                            <div class="text-warning">Belum Ada</div>
                                        @else
                                            <div class="text-info">{{ strtoupper($list->door->device_id) }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($list->door->socket_id == null)
                                            <div class="text-danger">Offline</div>
                                        @else
                                            <div class="text-info">Online</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($list->door->socket_id == null)
                                            <div class="text-warning">Tidak Diketahui</div>
                                        @else
                                            @if ($list->door->is_lock == true)
                                                <div class="text-info">Terkunci</div>
                                            @else
                                                <div class="text-danger">Tidak Terkunci</div>
                                            @endif
                                        @endif
                                    </td>
                                    @if ($insert_status == 'running')
                                        <td class="text-center">
                                            @if ($list->door->socket_id == null)
                                            <div style="font-family: monospace">-</div>
                                        @else
                                            <button wire:click="changeLocking('{{ $list->door->id }}', '{{ $list->door->is_lock == 1 ? 'open' : 'lock' }}', '{{ $list->door->token }}')" wire:loading.attr="disabled" class="btn btn-sm {{ $list->door->is_lock == 1 ? 'btn-primary' : 'btn-info' }} bg-gradient" style="width: 80px">
                                                <div wire:loading wire:target="changeLocking">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                </div>
                                                <i class="bi {{ $list->door->is_lock == 1 ? 'bi-unlock' : 'bi-lock' }} me-1" wire:loading.class="d-none" wire:target="changeLocking"></i>
                                                {{ $list->door->is_lock == 1 ? 'Buka' : 'Kunci' }}
                                            </button>
                                        @endif
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <button wire:click="deleteConfirm('{{ $list->id }}')" type="button" class="btn btn-sm btn-danger bg-gradient">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-2 rounded border border-secondary">
                        <div class="text-center mb-3 mt-3">-- tidak ada data --</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- add schedule form --}}
	<div wire:ignore.self class="modal fade" id="addScedule" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Tambah Jadwal Baru
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeScedule" id="scheduleForm">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="name" class="form-control bg-dark text-white @error('insert_name') is-invalid @enderror" wire:model.defer="insert_name" autocomplete="off" required>
                            @error('insert_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Mulai</span>
                                        <input type="date" class="form-control bg-dark text-white @error('insert_date_begin') is-invalid @enderror" wire:model.defer="insert_date_begin" required>
                                        @error('insert_date_begin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Sampai</span>
                                        <input type="date" class="form-control bg-dark text-white @error('insert_date_end') is-invalid @enderror" wire:model.defer="insert_date_end" required>
                                        @error('insert_date_end')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durasi Harian</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Mulai</span>
                                        <input type="time" class="form-control bg-dark text-white @error('insert_time_begin') is-invalid @enderror" wire:model.defer="insert_time_begin" required>
                                        @error('insert_time_begin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Sampai</span>
                                        <input type="time" class="form-control bg-dark text-white @error('insert_time_end') is-invalid @enderror" wire:model.defer="insert_time_end" required>
                                        @error('insert_time_end')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Perulangan</label>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Senin
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="senin" wire:model.defer="insert_day_0">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Selasa
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="selasa" wire:model.defer="insert_day_1">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Rabu
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="rabu" wire:model.defer="insert_day_2">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Kamis
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="kamis" wire:model.defer="insert_day_3">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Jumat
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="jumat" wire:model.defer="insert_day_4">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Sabtu
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="sabtu" wire:model.defer="insert_day_5">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Minggu
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="minggu" wire:model.defer="insert_day_6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addScedule')" wire:loading.attr='disabled' wire:target='storeScedule'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="scheduleForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <div wire:loading wire:target='storeScedule'>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            <i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='storeScedule'></i>
                            Tambahkan
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- edit schedule form --}}
	<div wire:ignore.self class="modal fade" id="editScedule" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Edit Jadwal
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateScedule" id="scheduleEditForm">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="name" class="form-control bg-dark text-white @error('edit_name') is-invalid @enderror" wire:model.defer="edit_name" autocomplete="off" required>
                            @error('edit_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Mulai</span>
                                        <input type="date" class="form-control bg-dark text-white @error('edit_date_begin') is-invalid @enderror" wire:model.defer="edit_date_begin" required>
                                        @error('edit_date_begin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Sampai</span>
                                        <input type="date" class="form-control bg-dark text-white @error('edit_date_end') is-invalid @enderror" wire:model.defer="edit_date_end" required>
                                        @error('edit_date_end')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durasi Harian</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Mulai</span>
                                        <input type="time" class="form-control bg-dark text-white @error('edit_time_begin') is-invalid @enderror" wire:model.defer="edit_time_begin" required>
                                        @error('edit_time_begin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Sampai</span>
                                        <input type="time" class="form-control bg-dark text-white @error('edit_time_end') is-invalid @enderror" wire:model.defer="edit_time_end" required>
                                        @error('edit_time_end')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ulangi</label>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Senin
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="senin" wire:model.defer="edit_day_0">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Selasa
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="selasa" wire:model.defer="edit_day_1">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Rabu
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="rabu" wire:model.defer="edit_day_2">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Kamis
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="kamis" wire:model.defer="edit_day_3">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Jumat
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="jumat" wire:model.defer="edit_day_4">
                                    </div>
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Sabtu
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="sabtu" wire:model.defer="edit_day_5">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-control bg-dark text-white mb-2 d-flex">
                                        Minggu
                                        <input class="form-check-input bg-dark border ms-auto" type="checkbox" value="minggu" wire:model.defer="edit_day_6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('editScedule')" wire:loading.attr='disabled' wire:target='updateScedule'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="scheduleEditForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <div wire:loading wire:target='updateScedule'>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            <i class="bi bi-pencil-square me-1" wire:loading.class='d-none' wire:target='updateScedule'></i>
                            Update
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- add door form --}}
	<div wire:ignore.self class="modal fade" id="addDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Tambah Pintu
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeDoor" id="doorForm">
                        <div class="mb-3">
                            <label class="form-label">Pintu</label>
                            <select class="form-select bg-dark text-white @error('') is-invalid @enderror" wire:model.defer="schedule_door_id" autocomplete="off" required>
                                <option hidden class="text-white">-- pilih salah satu --</option>
                                @foreach ($doors as $door)
                                    <option class="text-white" value="{{ $door->id }}">{{ $door->name }}</option>
                                @endforeach
                            </select>
                            @error('schedule_door_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addDoor')" wire:loading.attr='disabled' wire:target='storeDoor'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="doorForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <div wire:loading wire:target='storeDoor'>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            <i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='storeDoor'></i>
                            Tambahkan
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- delete confirm --}}
	<div wire:ignore.self class="modal fade" id="deleteConfirm" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Konfirmasi Hapus Data
                </div>
                <div class="modal-body">
                    Apakah anda yakin untuk mengapus <strong>{{ $delete_name }}</strong> secara permanen ?
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-primary ms-auto" wire:click="closeModal('deleteConfirm')" wire:loading.attr="disabled"
                            wire:target="delete">
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button wire:click="delete" wire:loading.attr="disabled" wire:target="closeModal('deleteConfirm')" class="btn btn-sm btn-danger ms-3">
                            <i class="bi bi-trash me-1" wire:loading.class="d-none" wire:target="delete"></i>
                            <div wire:loading wire:target="delete">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            Hapus
                        </button>
                    </div>
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
    <script>
        window.office = @json($office_id);
        window.connection_status = document.getElementById("connection_status");
    </script>
@endpush
