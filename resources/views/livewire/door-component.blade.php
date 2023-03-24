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
                <div class="col">
                    Daftar Pintu
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
                            --- Tidak Ada Data ---
                        </div>
                    @else
                        <thead>
                            <tr class="align-middle">
                                <td>No</td>
                                <td>Nama</td>
                                <td class="text-center">Perangkat Kunci</td>
                                <td class="text-center">Status Koneksi</td>
                                <td class="text-center">Status Penguncian</td>
                                <td class="text-center">Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doors as $index => $door)
                                <tr class="align-middle">
                                    <td>{{ $doors->firstItem() + $index }}</td>
                                    <td style="cursor: pointer;" wire:click="getDoorDetail('{{ $door->id }}')">{{ $door->name }}</td>
                                    <td class="text-center">{{ $door->device_id }}</td>
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
            <div class="card-header">
                Akses Pengguna
            </div>
            <div class="card-body">
                <div class="p-2 mb-5 rounded border border-secondary d-flex">
                    <div class="p-2 bg-white rounded" style="cursor: pointer" wire:click="openModal('qrCode')">
                        {!! QrCode::size(130)->generate($device_id) !!}
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
                            <td class="px-2">{{ $device_id }}</td>
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
                            <td class="px-2">Dipasang</td>
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
                    <button class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Pengguna</button>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search" placeholder="Cari Pengguna ..." wire:model="search" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- add door form --}}
	<div wire:ignore.self class="modal fade" id="addDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="addDoorLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Tambah Kunci Pintu Baru</div>
					<div class="card-body">
						<form wire:submit.prevent="storeUser" id="userForm">
							<div class="mb-4">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control bg-dark text-white @error('email') is-invalid @enderror"
									id="email" name="email" wire:model.defer="email" autocomplete="off">
								@error('email')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
                            <div class="mb-3">
								<label for="name" class="form-label">Nama</label>
								<input type="name" class="form-control bg-dark text-white @error('name') is-invalid @enderror" id="name"
									name="name" wire:model.defer="name" autocomplete="off">
								@error('name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
                            <div class="mb-4">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-select bg-dark text-white @error('gender') is-invalid @enderror" id="gender" name="gender" wire:model.defer="gender" autocomplete="off">
                                    <option hidden class="text-white">-- pilih salah satu --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
							<div class="mb-4">
								<strong>Pastikan email valid dan masih aktif.</strong>
								<div style="text-align: justify">
									pengguna akan menerima pesan melalui email yang berisi username, alamat email dan password (default) yang
									digunakan untuk login dan verifikasi.
								</div>
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

    {{-- edit door form --}}
    <div wire:ignore.self class="modal fade" id="editDoor" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card text-white rounded">
                    <div class="card-header">Edit Kunci Pintu</div>
                    <div class="card-body">
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
                            <div class="mb-3">
                                <label class="form-label">Device ID</label>
                                <input type="text" class="form-control bg-dark text-white @error('device_id_edited') is-invalid @enderror" name="device_id_edited" wire:model.defer="device_id_edited" autocomplete="off">
                                @error('device_id_edited')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </form>
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
    </div>

    {{-- delete confirm --}}
	<div wire:ignore.self class="modal fade" id="deleteConfirm" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Konfirmasi Hapus Data</div>
					<div class="card-body">
						Apakah anda yakin untuk mengapus <strong>{{ $name }}</strong> secara permanen ?
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

    {{-- qr code view --}}
    <div wire:ignore.self class="modal fade" id="qrCode" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card">
                    <div class="card-header">
                        Label Pintu
                    </div>
                    <div class="card-body">
                        <div class="bg-white">
                            test
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
