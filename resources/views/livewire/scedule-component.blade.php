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
                                <th class="text-center">Perangkat Kunci</th>
                                <th class="text-center">Status Koneksi</th>
                                <th class="text-center">Status Penguncian</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach ($doors as $index => $door)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $doors->firstItem() + $index }}</td>
                                    <td style="cursor: pointer;" wire:click="getDoorDetail('{{ $door->id }}')">{{ $door->name }}</td>
                                    <td class="text-center">
                                        @if ($door->device_id == null)
                                            <div class="text-warning">Belum Ada</div>
                                        @else
                                            <div class="text-info">{{ $door->device_id }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($door->socket_id == null)
                                            <div class="text-danger">Offline</div>
                                        @else
                                            <div class="text-info">Online</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($door->socket_id == null)
                                            <div class="text-warning">Tidak Diketahui</div>
                                        @else
                                            @if ($door->is_lock == true)
                                                <div class="text-info">Terkunci</div>
                                            @else
                                                <div class="text-danger">Tidak Terkunci</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="" type="button" class="btn btn-sm btn-primary bg-gradient" @if ($door->socket_id == null) disabled @endif>
                                            <i class="bi bi-door-open me-1"></i>
                                            Buka
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    @endif
                </table>
                {{ $scedules->links() }}
            </div>
        </div>
    @endif

    {{-- door detail --}}
    {{-- @if ($door_detail_visibility)
        <button wire:click="show_table" type="button" class="btn btn-sm btn-success bg-gradient mb-4">
            <i class="bi bi-arrow-left-square me-1"></i>
            Kembali
        </button>
        <div class="card">
            <div class="card-header">
                Akses Pengguna
            </div>
            <div class="card-body">
                <div class="p-2 mb-5 rounded border border-secondary d-flex">
                    <div class="p-2 bg-white rounded" style="cursor: pointer" wire:click="openModal('qrCode')">
                        {!! QrCode::size(130)->generate($door_url) !!}
                    </div>
                    <table class="ms-2">
                        <tr>
                            <td class="px-2">Nama</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $name }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Perangkat Kunci</td>
                            <td class="px-2">:</td>
                            <td class="px-2">
                                @if ($device_id == null)
                                    <div class="text-warning">Belum Ada</div>
                                @else
                                    <div class="text-info">{{ $device_id }}</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2">Status Koneksi</td>
                            <td class="px-2">:</td>
                            <td class="px-2">
                                @if ($socket_id == null)
                                    <div class="text-danger">Offline</div>
                                @else
                                    <div class="text-info">Online</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2">Status Penguncian</td>
                            <td class="px-2">:</td>
                            <td class="px-2">
                                @if ($socket_id == null)
                                    <div class="text-warning">Tidak Diketahui</div>
                                @else
                                    @if ($is_lock == true)
                                        <div class="text-info">Terkunci</div>
                                    @else
                                        <div class="text-danger">Tidak Terkunci</div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2">Dibuat</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $created_at }}</td>
                        </tr>
                    </table>
                    <div class="flex-grow-1 d-flex">
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-outline-primary me-2" wire:click="edit()"><div class="fs-6 text-white"><i class="bi bi-pencil-square"></i></div></button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="openModal('deleteConfirm')"><div class="fs-6 text-white"><i class="bi bi-trash"></i></div></button>
                        </div>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <button class="btn btn-sm btn-primary" wire:click="openModal('addAccess')"><i class="bi bi-plus-circle me-1"></i>Tambah Akses</button>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search" placeholder="Cari Akses ..." wire:model="searchAccess" autocomplete="off">
                    </div>
                </div>
                <div class="p-2 rounded border border-secondary">
                    @if (sizeof($access) != 0)
                        <table class="table text-white">
                            <thead>
                                <tr class="align-middle">
                                    <th class="text-center">No</th>
                                    <th>Nama</th>
                                    <th class="text-center">Durasi Harian</th>
                                    <th class="text-center">Batas Tanggal</th>
                                    <th class="text-center">Remote</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($access as $index => $list)
                                    <tr class="align-middle">
                                        <td class="text-center">{{ $access->firstItem() + $index }}</td>
                                        <td>{{ $list->user->name }}</td>
                                        <td class="text-center">{{ $list->time_begin. ' sd '. $list->time_end }}</td>
                                        @if ($list->is_temporary == 1)
                                            <td class="text-center">{{ $list->date_begin. ' sd '. $list->date_end }}</td>
                                        @else
                                            <td class="text-center text-info">Tidak Terbatas</td>
                                        @endif
                                        @if ($list->is_remote == 1)
                                            <td class="text-center text-warning">Ya</td>
                                        @else
                                            <td class="text-center text-info">Tidak</td>
                                        @endif
                                        @if ($list->is_running == 1)
                                            <td class="text-center text-info">Aktif</td>
                                        @else
                                            <td class="text-center text-warning">Pending</td>
                                        @endif
                                        <td class="text-center">
                                            <button wire:click="changeAccess('{{ $list->id }}')" wire:loading.attr="disabled" class="btn btn-sm text-white {{ ($list->is_running) == 1 ? 'btn-warning' : 'btn-info' }} me-1">
                                                <i class="bi {{ ($list->is_running) == 1 ? 'bi-pause-circle' : 'bi-play-circle' }} me-1" wire:loading.class="d-none" wire:target="changeAccess('{{ $list->id }}')"></i>
                                                <div wire:loading wire:target="changeAccess('{{ $list->id }}')">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                </div>
                                                {{ ($list->is_running) == 1 ? 'Blokir' : 'Aktifkan' }}
                                            </button>
                                            <button class="btn btn-sm btn-danger" wire:click="confirmDeleteAccess('{{ $list->id }}')"><i class="bi bi-trash me-1"></i>Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center mb-3 mt-3">-- tidak ada data --</div>
                    @endif
                </div>
            </div>
        </div>
    @endif --}}

    {{-- add access form --}}
	<div wire:ignore.self class="modal fade" id="addScedule" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Tambah Jadwal Baru</div>
					<div class="card-body">
						<form wire:submit.prevent="storeScedule" id="sceduleForm">
                            <div class="mb-3">
								<label class="form-label">Nama</label>
								<input type="name" class="form-control bg-dark text-white @error('name') is-invalid @enderror" wire:model.defer="name" autocomplete="off">
								@error('name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control bg-dark text-white @error('date') is-invalid @enderror" wire:model.defer="date" autocomplete="off">
								@error('date')
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
                                            <input type="time" class="form-control bg-dark text-white @error('access_time_begin') is-invalid @enderror" wire:model.defer="access_time_begin" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group mb-1">
                                            <span class="input-group-text bg-dark text-white">Sampai</span>
                                            <input type="time" class="form-control bg-dark text-white @error('access_time_end') is-invalid @enderror" wire:model.defer="access_time_end" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                <label class="form-check-label">
                                    Jadwal Berulang
                                </label>
                            </div>
                            {{-- <div class="row mb-4">
                                <div class="col-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Senin
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Jumat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Selasa
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Sabtu
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Rabu
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Minggu
                                        </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:model.defer="access_is_remote">
                                        <label class="form-check-label">
                                            Kamis
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
						</form>
						<div class="d-flex">
							<button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addScedule')" wire:loading.attr='disabled' wire:target='storeScedule'>
								<i class="bi bi-x-circle me-1"></i>
								Batal
							</button>
							<button type="submit" form="sceduleForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
								<div wire:loading wire:target='storeaScedule'>
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
</div>
