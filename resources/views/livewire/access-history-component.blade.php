<div>
    <div class="card text-white mb-4">
        <div class="card-header d-flex">
            <div class="text-white">
                Riwayat Akses Pengguna
            </div>
            {{-- <div class="ms-auto d-flex align-items-center">
                <div id="connection_status" style="color: {{ $connection_color }}">{{ $connection_status }}</div>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="mb-4 d-flex">
                <div class="col-8 col-md-3 ms-auto">
                    <input type="text" class="form-control form-control-sm bg-dark text-white" id="search" placeholder="Cari Riwayat ..." wire:model="search" autocomplete="off">
                </div>
            </div>
            <table class="table text-white mb-4">
                @if (sizeof($histories) == 0)
                    <div class="text-center text-white p-3">
                        --- tidak ada data ---
                    </div>
                @else
                    <thead>
                        <tr class="align-middle bg-secondary">
                            <th class="text-center" style="width: 60px">No</th>
                            <th>Email</th>
                            <th>Nama</th>
                            <th>Pintu</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $index => $history)
                            <tr class="align-middle" style="height: 60px">
                                <td class="text-center">{{ $histories->firstItem() + $index}}</td>
                                <td>{{ $history->user->email }}</td>
                                <td>{{ $history->user->name }}</td>
                                <td>{{ $history->door->name }}</td>
                                <td>{{ ucwords($history->log) }}</td>
                                <td>{{ $history->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
            {{ $histories->links() }}
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
