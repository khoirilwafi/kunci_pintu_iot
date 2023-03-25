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

    {{-- office table --}}
    <div class="card text-white mb-4">
		<div class="card-header d-flex">
			<div class="col">
				Daftar Gedung
			</div>
		</div>
		<div class="card-body">
			<div class="mb-4 d-flex">
				<div class="col-auto p-0">
					<button wire:click="openModal('addOffice')" type="button" class="btn btn-sm btn-success bg-gradient">
						<i class="bi bi-plus-circle me-1"></i>
						Tambah
					</button>
				</div>
				<div class="col-8 col-md-3 ms-auto">
					<input type="text" class="form-control form-control-sm bg-dark text-white" id="search"
						placeholder="Cari Gedung ..." wire:model="search" autocomplete="off">
				</div>
			</div>
			<table class="table text-white mb-4">
				@if (sizeof($offices) == 0)
					<div class="text-center text-white p-3">
						--- Tidak Ada Data ---
					</div>
				@else
					<thead>
						<tr class="align-middle">
							<th class="text-center">No</th>
							<th>Nama</th>
							<th>Operator</th>
							<th>Email</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($offices as $index => $office)
							<tr class="align-middle">
								<td class="text-center">{{ $offices->firstItem() + $index }}</td>
								<td>{{ $office->name }}</td>
								<td>{{ $office->user->name }}</td>
								<td>{{ $office->user->email }}</td>
								<td class="text-center">
                                    <button wire:click="edit('{{ $office->id }}')" type="button"class="btn btn-sm btn-primary bg-gradient me-1">
										<i class="bi bi-pencil-square me-1"></i>
										Edit
									</button>
									<button wire:click="deleteOfficeConfirm('{{ $office->id }}')" type="button" class="btn btn-sm btn-danger bg-gradient">
										<i class="bi bi-trash me-1"></i>
										Hapus
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				@endif
			</table>
			{{ $offices->links() }}
		</div>
	</div>

    {{-- add office form --}}
    <div wire:ignore.self class="modal fade" id="addOffice" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="addOfficeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card text-white rounded">
                    <div class="card-header">Tambah Gedung Baru</div>
                    <div class="card-body">
                        <form wire:submit.prevent="storeOffice" id="officeForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="name" class="form-control bg-dark text-white @error('name') is-invalid @enderror" id="name" name="name" wire:model.defer="name" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="user_id" class="form-label">Operator</label>
                                <select class="form-select bg-dark text-white @error('user_id') is-invalid @enderror" id="user_id" name="user_id" wire:model.defer="user_id" autocomplete="off">
                                    {{-- @if ($user_id != '')
                                        <option hidden selected class="text-white" value="{{ $user_id }}">{{ $operator_name }}</option>
                                    @else

                                    @endif --}}
                                    <option hidden class="text-white">-- pilih salah satu --</option>
                                    @foreach ($available_operators as $operator)
                                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </form>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('addOffice')" wire:loading.attr='disabled' wire:target='storeOffice'>
                                <i class="bi bi-x-circle me-1"></i>
                                Batal
                            </button>
                            <button type="submit" form="officeForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                                <div wire:loading wire:target='storeOffice'>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
                                <i class="bi bi-plus-circle me-1" wire:loading.class='d-none' wire:target='storeOffice'></i>
                                Tambahkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- edit office form --}}
    <div wire:ignore.self class="modal fade" id="editOffice" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="editOfficeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card text-white rounded">
                    <div class="card-header">Edit Gedung</div>
                    <div class="card-body">
                        <form wire:submit.prevent="updateOffice" id="officeEditForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="name" class="form-control bg-dark text-white @error('name') is-invalid @enderror" id="name" name="name" wire:model.defer="name" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="user_id" class="form-label">Operator</label>
                                <select class="form-select bg-dark text-white @error('user_id') is-invalid @enderror" id="user_id" name="user_id" wire:model.defer="user_id" autocomplete="off">
                                    @if ($user_id != '')
                                        <option hidden selected class="text-white" value="{{ $user_id }}">{{ $operator_name }}</option>
                                    @endif
                                    <option hidden class="text-white">-- pilih salah satu --</option>
                                    @foreach ($available_operators as $operator)
                                        <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </form>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('editOffice')" wire:loading.attr='disabled' wire:target='updateOffice'>
                                <i class="bi bi-x-circle me-1"></i>
                                Batal
                            </button>
                            <button type="submit" form="officeEditForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                                <div wire:loading wire:target='updateOffice'>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
                                <i class="bi bi-pencil-square me-1" wire:loading.class='d-none' wire:target='updateOffice'></i>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- delete confirm --}}
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
