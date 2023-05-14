<div>

    {{-- notification --}}
    <div id="notification">
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
        @if (session()->has('password_failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('password_failed') }}</strong> gagal diperbarui, confirmasi password tidak sesuai.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    {{-- profile card --}}
    <div class="card text-white mb-4">
		<div class="card-header">
			Profil Anda
		</div>
		<div class="card-body px-5 py-4">
            <div class="row mb-3">
                <div class="col-4 d-flex">
                    <img src="{{ url('/dashboard/my-account/avatar', [$avatar_file]) }}" alt="avatar" style="height:100%; max-height:45vh; max-width:100%;" class="mx-auto">
                </div>
                <div class="col-1"></div>
                <div class="col-7">
                    <div class="mb-4">
                        <div class="mb-3">
                            <label class="form-label mb-1">Nama</label>
                            <input class="form-control bg-dark text-white" value="{{ $user->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Jenis Kelamin</label>
                            <input class="form-control bg-dark text-white" value="{{ ucfirst($user->gender) }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Email</label>
                            <input class="form-control bg-dark text-white" value="{{ $user->email }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Nomor HP</label>
                            <input class="form-control bg-dark text-white" value="{{ ucfirst($user->phone) }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Role Akun</label>
                            <input class="form-control bg-dark text-white" value="{{ ucfirst($user->role) }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="d-grid mt-1">
                        <button class="btn btn-success" type="button" wire:click="openModal('editAvatar')"><i class="bi bi-upload me-2"></i>Upload Foto</button>
                    </div>
                </div>
                <div class="col-1"></div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="btn btn-primary" type="button" wire:click="editProfile"><i class="bi bi-pencil-square me-2"></i>Edit Profil</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="btn btn-danger" type="button" wire:click="openModal('confirmPassword')"><i class="bi bi-key me-2"></i>Ganti Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

		</div>
	</div>

    {{-- upload avatar modal --}}
    <div wire:ignore.self class="modal fade" id="editAvatar" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="editAvatarLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Upload Foto Profil
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeAvatar" id="avatarForm">
                        <div class="mb-3 mt-2">
                            <input class="form-control bg-dark text-white @error('avatar') is-invalid @enderror" type="file" id="avatar{{ $iteration }}" wire:model="avatar" name="avatar">
                            @error('avatar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <ul>
                                <li>Format jpg, jpeg, png</li>
                                <li>Ukuran maksimal 1 MB</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('editAvatar')" wire:loading.attr='disabled' wire:target='storeAvatar'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="avatarForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <i class="bi bi-upload me-1"></i>
                            Upload
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- edit profil modal --}}
    <div wire:ignore.self class="modal fade" id="editProfile" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Edit Profil
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeProfile" id="profileForm" class="mb-4">
                        <div class="mb-3">
                            <label class="form-label mb-1">Nama</label>
                            <input class="form-control bg-dark text-white @error('name') is-invalid @enderror" wire:model.defer="name" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1 @error('gender') is-invalid @enderror">Jenis Kelamin</label>
                            <select class="form-select bg-dark text-white @error('gender') is-invalid @enderror" id="gender" name="gender" wire:model.defer="gender" required>
                                <option value="laki-laki" {{ (auth()->user()->gender == 'laki-laki') ? 'selected' : ''}}>Laki-laki</option>
                                <option value="perempuan" {{ (auth()->user()->gender == 'perempuan') ? 'selected' : ''}}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Email</label>
                            <input class="form-control bg-dark text-white @error('email') is-invalid @enderror" wire:model.defer="email" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Nomor HP</label>
                            <input class="form-control bg-dark text-white @error('phone') is-invalid @enderror" wire:model.defer="phone" required>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('editProfile')" wire:loading.attr='disabled' wire:target='storeProfile'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="profileForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <i class="bi bi-arrow-down-square-fill me-1"></i>
                            Simpan
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- confirm password modal --}}
    <div wire:ignore.self class="modal fade" id="confirmPassword" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="confirmPasswordLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Konfirmasi Password
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div style="text-align: justify">
                            Silahkan masukkan password anda saat ini.
                        </div>
                    </div>
                    <form wire:submit.prevent="confirm" id="passwordForm" class="mb-3">
                        <div class="mb-3">
                            <input type="password" class="form-control bg-dark text-white @error('password') is-invalid @enderror" wire:model.defer="password" required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('confirmPassword')" wire:loading.attr='disabled' wire:target='confirm'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="passwordForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <i class="bi bi-send-fill me-1"></i>
                            Kirim
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- change password modal --}}
    <div wire:ignore.self class="modal fade" id="changePassword" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
                <div class="modal-header">
                    Ganti Password
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storePassword" id="changePasswordForm" class="mb-4">
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control bg-dark text-white @error('password') is-invalid @enderror" wire:model.defer="password" required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control bg-dark text-white @error('password_confirmation') is-invalid @enderror" wire:model.defer="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button class="btn btn-sm btn-secondary ms-auto" wire:click="closeModal('changePassword')" wire:loading.attr='disabled' wire:target='storePassword'>
                            <i class="bi bi-x-circle me-1"></i>
                            Batal
                        </button>
                        <button type="submit" form="changePasswordForm" class="btn btn-sm btn-primary ms-3" wire:loading.attr='disabled'>
                            <i class="bi bi-arrow-down-square-fill me-1"></i>
                            Simpan
                        </button>
                    </div>
                </div>
			</div>
		</div>
	</div>

    {{-- loading modal --}}
    <div wire:loading.flex class="align-items-center justify-content-center" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; overflow:hidden; background-color:rgba(0, 0, 0, 0.7);">
        <div class="bg-dark rounded border border-light p-4 d-flex align-items-center">
            <div class="spinner-border text-primary me-3" role="status">
            </div>
            <div class="fs-4">Loading ...</div>
        </div>
    </div>

</div>
