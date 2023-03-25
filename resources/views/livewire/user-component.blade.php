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
    </div>

    {{-- users table --}}
    @if($table_visibility)
        <div class="card text-white mb-4">
            <div class="card-header d-flex">
                <div class="col">
                    Daftar Pengguna
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4 d-flex">
                    <div class="col-auto p-0">
                        <button wire:click="openModal('addUser')" type="button" class="btn btn-sm btn-success bg-gradient">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search"
                            placeholder="Cari Pengguna ..." wire:model="search" autocomplete="off">
                    </div>
                </div>
                <table class="table text-white mb-4">
                    @if (sizeof($users) == 0)
                        <div class="text-center text-white p-3">
                            --- Tidak Ada Data ---
                        </div>
                    @else
                        <thead>
                            <tr class="align-middle">
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr class="align-middle">
                                    <td class="text-center">{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->gender }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td class="text-center">
                                        @if ($user->email_verified_at == null)
                                            <div class="text-warning">Belum Verifikasi</div>
                                        @else
                                            <div class="text-info">Aktif</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="getUserDetail('{{ $user->id }}')" type="button" class="btn btn-sm btn-primary bg-gradient me-1">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat
                                        </button>
                                        <button wire:click="deleteUserConfirm('{{ $user->id }}')" type="button" class="btn btn-sm btn-danger bg-gradient">
                                            <i class="bi bi-trash me-1"></i>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                {{ $users->links() }}
            </div>
        </div>
    @endif

    {{-- user detail --}}
    @if($user_detail_visibility)
        <button wire:click="tampilkan_table" type="button" class="btn btn-sm btn-success bg-gradient mb-4">
            <i class="bi bi-arrow-left-square me-1"></i>
            Kembali
        </button>
        <div class="card">
            <div class="card-header">
                Akses Pengguna
            </div>
            <div class="card-body">
                <div class="p-2 mb-5 rounded border border-secondary d-flex">
                    <img src="{{ url('my-account/avatar/'. $avatar_name) }}" alt="picture" height="100px" width="100px" class="ms-1">
                    <table class="ms-2">
                        <tr>
                            <td class="px-2">Nama</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $name }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Jenis Kelamin</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $gender }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Email</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $email }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Nomor HP</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $phone }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">Bergabung</td>
                            <td class="px-2">:</td>
                            <td class="px-2">{{ $created_at }}</td>
                        </tr>
                    </table>
                </div>
                <div class="d-flex mb-3">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Akses</button>
                    <div class="col-8 col-md-3 ms-auto">
                        <input type="text" class="form-control form-control-sm bg-dark text-white" id="search"
                            placeholder="Cari Akses ..." wire:model="search" autocomplete="off">
                    </div>
                </div>
                <div class="border border-secondary rounded d-flex mb-2">
                    <div class="p-3 d-flex flex-column border-end border-secondary"><i class="bi bi-key fs-4 my-auto"></i></div>
                    <div class="p-2 w-100">
                        <div class="d-flex">
                            <div>
                                <div class="fs-6 fw-bold">Ruang Dosen</div>
                                <div class="lh-1">
                                    <small>Gedung Teknik Kimia</small>
                                </div>
                            </div>
                            <div class="ms-auto"><small><i class="bi bi-clock me-2"></i>05.00 AM s/d 18.00 PM</small></div>
                        </div>
                        <div class="d-flex">
                            <div class="text-warning mt-auto"><small>QR Remote</small></div>
                            <button class="btn btn-sm btn-primary ms-auto me-2"><i class="bi bi-pause-circle me-1"></i>Blokir</button>
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- add user form --}}
	<div wire:ignore.self class="modal fade" id="addUser" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="card text-white rounded">
					<div class="card-header">Tambah Pengguna Baru</div>
					<div class="card-body">
						<form wire:submit.prevent="storeUser" id="userForm">
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
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control bg-dark text-white @error('email') is-invalid @enderror"
									id="email" name="email" wire:model.defer="email" autocomplete="off">
								@error('email')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
                            <div class="mb-4">
								<label for="phone" class="form-label">Nomor HP</label>
								<input type="number" class="form-control bg-dark text-white @error('phone') is-invalid @enderror"
									id="phone" name="phone" wire:model.defer="phone" autocomplete="off">
								@error('phone')
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
							<button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addUser')" wire:loading.attr='disabled' wire:target='storeUser'>
								<i class="bi bi-x-circle me-1"></i>
								Batal
							</button>
							<button type="submit" form="userForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
								<div wire:loading wire:target='storeUser'>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
								<i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='storeUser'></i>
								Tambahkan
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    {{-- modal delete confirm --}}
	<div wire:ignore.self class="modal fade" id="deleteConfirm" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
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

</div>
