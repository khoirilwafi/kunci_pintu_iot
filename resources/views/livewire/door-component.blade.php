<div>
    {{-- users table --}}
    @if(true)
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
                                <td>Device ID</td>
                                <td>Nama</td>
                                <td class="text-center">Koneksi</td>
                                <td class="text-center">Penguncian</td>
                                <td class="text-center">Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doors as $index => $door)
                                <tr class="align-middle">
                                    <td>{{ $doors->firstItem() + $index }}</td>
                                    <td>{{ $door->device_id }}</td>
                                    <td>{{ $door->name }}</td>
                                    <td class="text-center">offline</td>
                                    <td class="text-center">terkunci</td>
                                    <td class="text-center">
                                        <button wire:click="" type="button" class="btn btn-sm btn-primary bg-gradient me-1">
                                            <i class="bi bi-trash me-1"></i>
                                            Lihat
                                        </button>
                                        <button wire:click="" type="button" class="btn btn-sm btn-warning bg-gradient text-white me-1">
                                            <i class="bi bi-trash me-1"></i>
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
    @endif
</div>
