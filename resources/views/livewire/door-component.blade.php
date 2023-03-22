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

                </table>
            </div>
        </div>
    @endif
</div>
