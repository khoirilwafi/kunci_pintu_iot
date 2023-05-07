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

    {{-- door table --}}
    @if ($door_table_visibility)
        <div class="card text-white mb-4">
            <div class="card-header d-flex">
                <div class="text-white">
                    Daftar Pintu
                </div>
                <div class="ms-auto d-flex align-items-center">
                    <div id="connection_status" style="color: {{ $connection_color }}">{{ $connection_status }}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4 d-flex">
                    <div class="col-auto p-0">
                        <button wire:click="openModal('addDoor')" type="button" class="btn btn-sm btn-success bg-gradient">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search"
                            placeholder="Cari Pintu ..." wire:model="search" autocomplete="off">
                    </div>
                </div>
                <table class="table text-white mb-4">
                    @if (sizeof($doors) == 0)
                        <div class="text-center text-white p-3">
                            --- tidak ada data ---
                        </div>
                    @else
                        <thead>
                            <tr class="align-middle bg-secondary">
                                <th class="text-center" style="width: 50px">No</th>
                                <th>Nama</th>
                                <th class="text-center" style="width: 230px">Perangkat Kunci</th>
                                <th class="text-center" style="width: 100px">Koneksi</th>
                                <th class="text-center" style="width: 160px">Penguncian</th>
                                <th class="text-center" style="width: 120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doors as $index => $door)
                                <tr class="align-middle" style="height:60px">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td style="cursor: pointer" wire:click="getDoorDetail('{{ $door->id }}')">{{ $door->name }}</td>
                                    <td class="text-center">
                                        @if ($door->device_name === null)
                                            <div class="text-warning">Belum Ada</div>
                                        @else
                                            <div class="text-info" style="font-family: monospace">{{ strtoupper($door->device_name) }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center" id="door_connection_{{ $door->id }}">
                                        @if ($door->socket_id == null)
                                            <div class="text-danger">Offline</div>
                                        @else
                                            <div class="text-info">Online</div>
                                        @endif
                                    </td>
                                    <td class="text-center" id="door_locking_{{ $door->id }}">
                                        @if ($door->socket_id == null)
                                            <div class="text-warning">Tidak Diketahui</div>
                                        @else
                                            @if ($door->is_lock == 1)
                                                <div class="text-info">Terkunci</div>
                                            @else
                                                <div class="text-danger">Tidak Terkunci</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($door->socket_id == null)
                                            <div style="font-family: monospace">-</div>
                                        @else
                                            <button wire:click="changeLocking('{{ $door->id }}', '{{ $door->is_lock == 1 ? 'open' : 'lock' }}', '{{ $door->key }}')" wire:loading.attr="disabled" class="btn btn-sm {{ $door->is_lock == 1 ? 'btn-primary' : 'btn-info' }} bg-gradient" style="width: 80px">
                                                <div wire:loading wire:target="changeLocking">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                </div>
                                                <i class="bi {{ $door->is_lock == 1 ? 'bi-unlock' : 'bi-lock' }} me-1" wire:loading.class="d-none" wire:target="changeLocking"></i>
                                                {{ $door->is_lock == 1 ? 'Buka' : 'Kunci' }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    @endif

    {{-- door detail --}}
    @if ($door_detail_visibility)
        <button wire:click="show_table" type="button" class="btn btn-sm btn-success bg-gradient mb-4">
            <i class="bi bi-arrow-left-square me-1"></i>
            Kembali
        </button>
        <div class="card">
            <div class="card-header d-flex">
                <div class="text-white">
                    Detail Pintu - Akses Pengguna
                </div>
                <div class="ms-auto d-flex align-items-center">
                    <div id="connection_status">{{ $connection_status }}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="p-2 mb-5 rounded border border-secondary d-flex">
                    <div class="p-2 bg-white rounded">
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
                                @if ($device_name == null)
                                    <div class="text-warning">Belum Ada</div>
                                @else
                                    <div class="text-info" style="font-family: monospace">{{ strtoupper($device_name) }}</div>
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
                            @if ($device_name != null)
                                <button class="btn btn-sm btn-outline-info me-1" wire:click="printPoster"><div class="fs-6 text-white"><i class="bi bi-printer"></i></div></button>
                                <button class="btn btn-sm btn-outline-warning me-1" wire:click="openModal('unlinkDoor')"><div class="fs-6 text-white"><i class="bi bi-cpu"></i></button>
                            @endif
                            <button class="btn btn-sm btn-outline-primary me-1" wire:click="edit()"><div class="fs-6 text-white"><i class="bi bi-pencil-square"></i></div></button>
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
                @if (sizeof($access) != 0)
                    <table class="table text-white">
                        <thead>
                            <tr class="align-middle bg-secondary">
                                <th class="text-center" style="width: 60px">No</th>
                                <th>Nama</th>
                                <th class="text-center" style="width: 220px">Durasi Harian</th>
                                <th class="text-center" style="width: 220px">Batas Tanggal</th>
                                <th class="text-center" style="width: 100px">Status</th>
                                <th class="text-center" style="width: 200px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($access as $index => $list)
                                <tr class="align-middle" style="height: 60px">
                                    <td class="text-center">{{ $access->firstItem() + $index }}</td>
                                    <td>{{ $list->user->name }}</td>
                                    <td class="text-center">{{ $list->time_begin. ' sd '. $list->time_end }}</td>
                                    @if ($list->is_temporary == 1)
                                        <td class="text-center">{{ $list->date_begin. ' sd '. $list->date_end }}</td>
                                    @else
                                        <td class="text-center text-info">Tidak Terbatas</td>
                                    @endif
                                    @if ($list->is_running == 1)
                                        <td class="text-center text-info">Aktif</td>
                                    @else
                                        <td class="text-center text-warning">Pending</td>
                                    @endif
                                    <td class="text-center">
                                        <button wire:click="changeAccess('{{ $list->id }}')" wire:loading.attr="disabled" class="btn btn-sm {{ $list->is_running == 1 ? 'btn-warning' : 'btn-info' }} me-1" style="width: 100px;">
                                            <i class="bi {{ $list->is_running == 1 ? 'bi-pause-circle' : 'bi-play-circle' }} me-1" wire:loading.class="d-none" wire:target="changeAccess('{{ $list->id }}')"></i>
                                            <div wire:loading wire:target="changeAccess('{{ $list->id }}')">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </div>
                                            {{ $list->is_running == 1 ? 'Blokir' : 'Aktifkan' }}
                                        </button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDeleteAccess('{{ $list->id }}')"><i class="bi bi-trash me-1"></i>Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $access->links(); }}
                @else
                    <div class="p-2 rounded border border-secondary">
                        <div class="text-center mb-3 mt-3">-- tidak ada data --</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- add door form --}}
	<div wire:ignore.self class="modal fade" id="addDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Tambah Kunci Pintu Baru
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeDoor" id="doorForm">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control bg-dark text-white @error('name') is-invalid @enderror" name="name" wire:model.defer="name" autocomplete="off">
                            @error('name')
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

    {{-- add access form --}}
	<div wire:ignore.self class="modal fade" id="addAccess" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Tambah Akses Baru
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeAccess" id="accessForm">
                        <div class="mb-3">
                            <label class="form-label">Pengguna</label>
                            <select class="form-select bg-dark text-white @error('access_user_id') is-invalid @enderror" wire:model.defer="access_user_id" autocomplete="off" required>
                                <option hidden class="text-white">-- pilih salah satu --</option>
                                @foreach ($users as $user)
                                    <option class="text-white" value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('access_user_id')
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
                                        @error('access_time_begin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Sampai</span>
                                        <input type="time" class="form-control bg-dark text-white @error('access_time_end') is-invalid @enderror" wire:model.defer="access_time_end" required>
                                        @error('access_time_end')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input bg-dark border" type="checkbox" value="1" wire:click="showDate" wire:model.defer="access_is_temporary">
                            <label class="form-check-label">
                                Akses Sementara
                            </label>
                        </div>
                        <div class="mb-4 @if(!$date_visibility) d-none @endif">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Mulai</span>
                                        <input type="date" class="form-control bg-dark text-white @error('access_date_begin') is-invalid @enderror" wire:model.defer="access_date_begin">
                                        @error('access_date_begin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-dark text-white">Sampai</span>
                                        <input type="date" class="form-control bg-dark text-white @error('access_date_end') is-invalid @enderror" wire:model.defer="access_date_end">
                                        @error('access_date_end')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addAccess')" wire:loading.attr='disabled' wire:target='storeAccess'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="accessForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <div wire:loading wire:target='storeAccess'>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            <i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='storeAccess'></i>
                            Tambahkan
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- edit door form --}}
    <div wire:ignore.self class="modal fade" id="editDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    Edit Perangkat Kunci Pintu
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateDoor" id="doorEditForm">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="name" class="form-control bg-dark text-white @error('name_edited') is-invalid @enderror" name="name_edited" wire:model.defer="name_edited" autocomplete="off">
                            @error('name_edited')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('editDoor')" wire:loading.attr='disabled' wire:target='updateDoor'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="doorEditForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <div wire:loading wire:target='updateDoor'>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            <i class="bi bi-pencil-square me-1" wire:loading.class='d-none' wire:target='updateDoor'></i>
                            Update
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
                    Apakah anda yakin untuk mengapus <strong>{{ $name }}</strong> secara permanen ?
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

    {{-- unlink confirm --}}
	<div wire:ignore.self class="modal fade" id="unlinkDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Konfirmasi Lepas Perangkat Kunci
                </div>
                <div class="modal-body">
                    Apakah anda yakin untuk melepas perangkat <strong>{{ strtoupper($device_name) }}</strong> secara permanen ?
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-primary ms-auto" wire:click="closeModal('unlinkDoor')" wire:loading.attr="disabled"
                            wire:target="unlink">
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button wire:click="unlink" wire:loading.attr="disabled" wire:target="closeModal('unlinkDoor')" class="btn btn-sm btn-danger ms-3">
                            <i class="bi bi-scissors me-1" wire:loading.class="d-none" wire:target="unlink"></i>
                            <div wire:loading wire:target="unlink">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            Lepas
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- delete access confirm --}}
	<div wire:ignore.self class="modal fade" id="deleteAccessConfirm" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Konfirmasi Hapus Akses
                </div>
                <div class="modal-body">
                    Apakah anda yakin untuk mengapus akses <strong>{{ $access_user_name }}</strong> di pintu <strong>{{ $access_door_name }}</strong> secara permanen ?
                </div>
                <div class="modal-footer">
                    <div class="d-flex mt-3">
                        <button class="btn btn-sm btn-primary ms-auto" wire:click="closeModal('deleteAccessConfirm')" wire:loading.attr="disabled"
                            wire:target="deleteAccess">
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button wire:click="deleteAccess" wire:loading.attr="disabled" wire:target="closeModal('deleteAccessConfirm')" class="btn btn-sm btn-danger ms-3">
                            <i class="bi bi-trash me-1" wire:loading.class="d-none" wire:target="deleteAccess"></i>
                            <div wire:loading wire:target="deleteAccess">
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
    <div wire:ignore.self class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="alertModalLabel">
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
