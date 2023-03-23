<div>

    {{-- door table --}}
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
                                <td>{{ $door->name }}</td>
                                <td class="text-center">
                                    @if ($door->device_id == null)
                                        <div class="text-warning">Belum Tersedia</div>
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
                                    <button wire:click="" type="button" class="btn btn-sm btn-primary bg-gradient me-1">
                                        <i class="bi bi-eye me-1"></i>
                                        Lihat
                                    </button>
                                    <button wire:click="" type="button" class="btn btn-sm btn-warning bg-gradient text-white me-1">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </button>
                                    <button wire:click="" type="button" class="btn btn-sm btn-danger bg-gradient">
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
</div>
