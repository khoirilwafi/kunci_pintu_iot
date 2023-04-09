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

    {{-- operator table --}}
	<div class="card text-white mb-4">
		<div class="card-header d-flex">
			<div class="col">
				Daftar Operator
			</div>
		</div>
		<div class="card-body">
			<div class="mb-4 d-flex">
				<div class="col-auto p-0">
					<button wire:click="openModal('addOperator')" type="button" class="btn btn-sm btn-success bg-gradient">
						<i class="bi bi-plus-circle me-1"></i>
						Tambah
					</button>
				</div>
				<div class="col-8 col-md-3 ms-auto">
					<input type="text" class="form-control form-control-sm bg-dark text-white" id="search"
						placeholder="Cari Operator ..." wire:model="search" autocomplete="off">
				</div>
			</div>
			<table class="table text-white mb-4">
				@if (sizeof($operators) == 0)
					<div class="text-center text-white p-3">
						--- Tidak Ada Data ---
					</div>
				@else
					<thead>
						<tr class="align-middle bg-secondary">
							<th class="text-center" style="width: 60px">No</th>
							<th>Nama</th>
							<th>Email</th>
							<th>Gedung</th>
							<th class="text-center" style="width: 120px">Jenis Kelamin</th>
							<th class="text-center" style="width: 70px">Nomor HP</th>
							<th class="text-center" style="width: 150px">Status</th>
							<th class="text-center" style="width: 120px">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($operators as $index => $operator)
							<tr class="align-middle" style="height: 60px">
								<td class="text-center">{{ $operators->firstItem() + $index }}</td>
								<td>{{ $operator->name }}</td>
								<td>{{ $operator->email }}</td>
								<td>
									@if ($operator->office != null)
										<div class="text-info">{{ $operator->office->name }}</div>
									@else
										<div class="text-warning">Belum Ada</div>
									@endif
								</td>
                                <td class="text-center">{{ $operator->gender }}</td>
								<td class="text-center">{{ $operator->phone }}</td>
                                <td class="text-center">
									@if ($operator->email_verified_at == null)
                                        <div class="text-warning">Belum Verifikasi</div>
                                    @else
                                        <div class="text-success">Aktif</div>
                                    @endif
								</td>
								<td class="text-center">
									<button wire:click="deleteOperatorConfirm('{{ $operator->id }}')" type="button" class="btn btn-sm btn-danger bg-gradient">
										<i class="bi bi-trash me-1"></i>
										Hapus
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				@endif
			</table>
			{{ $operators->links() }}
		</div>
	</div>

    {{-- add operator form --}}
	<div wire:ignore.self class="modal fade" id="addOperator" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="addOperatorLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Tambah Operator Baru
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeOperator" id="operatorForm">
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
                                operator akan menerima pesan melalui email yang berisi username, alamat email dan password (default) yang
                                digunakan untuk login dan verifikasi.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addOperator')" wire:loading.attr='disabled' wire:target='storeOperator'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="operatorForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <div wire:loading wire:target='storeOperator'>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                            <i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='storeOperator'></i>
                            Tambahkan
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- modal delete confirm --}}
	<div wire:ignore.self class="modal fade" id="deleteConfirm" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
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

</div>
