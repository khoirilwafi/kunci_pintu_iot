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

    {{-- scedule table --}}
    @if ($scedule_table_visibility)
        <div class="card text-white mb-4">
            <div class="card-header d-flex">
                <div class="col">
                    Daftar Jadwal
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
                    @if (sizeof($scedules) == 0)
                        <div class="text-center text-white p-3">
                            --- tidak ada data ---
                        </div>
                    @else
                        <thead>
                            <tr class="align-middle">
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Mulai</th>
                                <th class="text-center">Berakhir</th>
                                <th class="text-center">Berulang</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scedules as $index => $scedule)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $scedules->firstItem() + $index }}</td>
                                    <td>{{ $scedule->name }}</td>
                                    <td class="text-center">{{ $scedule->date_running }}</td>
                                    <td class="text-center">{{ $scedule->time_begin }}</td>
                                    <td class="text-center">{{ $scedule->time_end }}</td>
                                    <td class="text-center">{{ ($scedule->is_repeating == 1) ? 'Ya' : 'Tidak' }}</td>
                                    <td class="text-center">
                                        <button wire:click="getSceduleDetail('{{ $scedule->id }}')" type="button" class="btn btn-sm btn-primary bg-gradient me-1">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat
                                        </button>
                                        <button wire:click="deleteConfirm('{{ $scedule->id }}')" type="button" class="btn btn-sm btn-danger bg-gradient">
                                            <i class="bi bi-trash me-1"></i>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                {{ $scedules->links() }}
            </div>
        </div>
    @endif

    {{-- scedule detail --}}
    @if ($scedule_detail_visibility)
        <button wire:click="showTable" type="button" class="btn btn-sm btn-success bg-gradient mb-4">
            <i class="bi bi-arrow-left-square me-1"></i>
            Kembali
        </button>
        <div class="card">
            <div class="card-header">
                Detail Jadwal
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
                            <td class="px-2">Tanggal</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $insert_date }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Mulai</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $insert_time_begin }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Berakhir</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $insert_time_end }}</td>
                        </tr>
                        @if ($insert_is_repeat == 1)
                            <tr>
                                <td class="px-2">Berulang</td>
                                <td class="px-2">:</td>
                                <td class="px-2">
                                    @if ($insert_day_0 == 1) Senin,  @endif
                                    @if ($insert_day_1 == 1) Selasa, @endif
                                    @if ($insert_day_2 == 1) Rabu,   @endif
                                    @if ($insert_day_3 == 1) Kamis,  @endif
                                    @if ($insert_day_4 == 1) Jumat,  @endif
                                    @if ($insert_day_5 == 1) Sabtu,  @endif
                                    @if ($insert_day_6 == 1) Minggu, @endif
                                </td>
                            </tr>
                        @endif
                    </table>
                    <div class="flex-grow-1 d-flex">
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-outline-primary me-2" wire:click="edit"><div class="fs-6 text-white"><i class="bi bi-pencil-square"></i></div></button>
                        </div>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <button class="btn btn-sm btn-primary" wire:click="openModal('addDoor')"><i class="bi bi-plus-circle me-1"></i>Tambah Pintu</button>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search" placeholder="Cari Akses ..." wire:model="searchAccess" autocomplete="off">
                    </div>
                </div>
                <div class="p-2 rounded border border-secondary">
                    @if (sizeof($door_links) != 0)
                        <table class="table text-white">
                            <thead>
                                <tr class="align-middle">
                                    <th class="text-center">No</th>
                                    <th>Nama</th>
                                    <th class="text-center">Device ID</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Penguncian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($door_links as $index => $list)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ $door_links->firstItem() + $index }}</td>
                                        <td>{{ $list->door->name }}</td>
                                        <td class="text-center">
                                            @if ($list->door->device_id == null)
                                                <div class="text-warning">Belum Ada</div>
                                            @else
                                                <div class="text-info">{{ $list->door->device_id }}</div>
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
                                        <td class="text-center">
                                            <button wire:click="" type="button" class="btn btn-sm btn-primary bg-gradient me-1" @if ($list->door->socket_id == null) disabled @endif>
                                                <i class="bi bi-door-open me-1"></i>
                                                Buka
                                            </button>
                                            <button wire:click="" type="button" class="btn btn-sm btn-danger bg-gradient">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $door_links->links(); }}
                    @else
                        <div class="text-center mb-3 mt-3">-- tidak ada data --</div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- add schedule form --}}
	<div wire:ignore.self class="modal fade" id="addScedule" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Tambah Jadwal Baru</div>
					<div class="card-body">
						<form wire:submit.prevent="storeScedule" id="sceduleForm">
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
                                <input type="date" class="form-control bg-dark text-white @error('insert_date') is-invalid @enderror" wire:model.defer="insert_date" autocomplete="off" required>
								@error('insert_date')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
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
                            <div class="form-check mb-4">
                                <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_is_repeat" wire:click="addDay">
                                <label class="form-check-label">
                                    Jadwal Berulang
                                </label>
                            </div>
                            @if ($insert_day)
                                <div class="row mb-4">
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_0">
                                            <label class="form-check-label">
                                                Senin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_4">
                                            <label class="form-check-label">
                                                Jumat
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_1">
                                            <label class="form-check-label">
                                                Selasa
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_5">
                                            <label class="form-check-label">
                                                Sabtu
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_2">
                                            <label class="form-check-label">
                                                Rabu
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_6">
                                            <label class="form-check-label">
                                                Minggu
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="insert_day_3">
                                            <label class="form-check-label">
                                                Kamis
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
						</form>
						<div class="d-flex">
							<button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addScedule')" wire:loading.attr='disabled' wire:target='storeScedule'>
								<i class="bi bi-x-circle me-1"></i>
								Batal
							</button>
							<button type="submit" form="sceduleForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
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
	</div>

    {{-- edit schedule form --}}
	<div wire:ignore.self class="modal fade" id="editScedule" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Edit Jadwal Baru</div>
					<div class="card-body">
						<form wire:submit.prevent="updateScedule" id="sceduleEditForm">
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
                                <input type="date" class="form-control bg-dark text-white @error('edit_date') is-invalid @enderror" wire:model.defer="edit_date" autocomplete="off" required>
								@error('edit_date')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
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
                            <div class="form-check mb-4">
                                <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_is_repeat" wire:click="editDay">
                                <label class="form-check-label">
                                    Jadwal Berulang
                                </label>
                            </div>
                            @if ($insert_day)
                                <div class="row mb-4">
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_0">
                                            <label class="form-check-label">
                                                Senin
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_4">
                                            <label class="form-check-label">
                                                Jumat
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_1">
                                            <label class="form-check-label">
                                                Selasa
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_5">
                                            <label class="form-check-label">
                                                Sabtu
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_2">
                                            <label class="form-check-label">
                                                Rabu
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_6">
                                            <label class="form-check-label">
                                                Minggu
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="edit_day_3">
                                            <label class="form-check-label">
                                                Kamis
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
						</form>
						<div class="d-flex">
							<button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('editScedule')" wire:loading.attr='disabled' wire:target='updateScedule'>
								<i class="bi bi-x-circle me-1"></i>
								Batal
							</button>
							<button type="submit" form="sceduleEditForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
								<div wire:loading wire:target='updateScedule'>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
								<i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='updateScedule'></i>
								Tambahkan
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    {{-- add access form --}}
	<div wire:ignore.self class="modal fade" id="addDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Tambah Pintu</div>
					<div class="card-body">
						<form wire:submit.prevent="storeDoor" id="doorForm">
                            <div class="mb-3">
                                <label class="form-label">Pintu</label>
                                <select class="form-select bg-dark text-white @error('') is-invalid @enderror" wire:model.defer="scedule_door_id" autocomplete="off" required>
                                    <option hidden class="text-white">-- pilih salah satu --</option>
                                    @foreach ($doors as $door)
                                        <option class="text-white" value="{{ $door->id }}">{{ $door->name }}</option>
                                    @endforeach
                                </select>
                                @error('scedule_door_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
						</form>
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
	</div>

    {{-- delete confirm --}}
	<div wire:ignore.self class="modal fade" id="deleteConfirm" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Konfirmasi Hapus Data</div>
					<div class="card-body">
						Apakah anda yakin untuk mengapus <strong>{{ $delete_name }}</strong> secara permanen ?
						<div class="d-flex mt-3">
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
	</div>
</div>
