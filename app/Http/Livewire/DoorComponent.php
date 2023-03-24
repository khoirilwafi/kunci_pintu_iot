<?php

namespace App\Http\Livewire;

use App\Models\Door;
use App\Models\Office;
use Livewire\Component;
use Livewire\WithPagination;

class DoorComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $device_id, $socket_id, $is_lock, $created_at;
    public $search;

    public $door_table_visibility  = true;
    public $door_detail_visibility = false;

    public function render()
    {
        $office = Office::where('user_id', request()->user()->id)->first();
        $data['doors'] = Door::where('name', 'like', '%' . $this->search . '%')->where('office_id', $office->id)->paginate(7);

        return view('livewire.door-component', $data);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetModal()
    {
        $this->name = '';

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModal($modal_id)
    {
        $this->resetModal();
        $this->dispatchBrowserEvent('modal_open', $modal_id);
    }

    public function closeModal($modal_id)
    {
        $this->resetModal();
        $this->dispatchBrowserEvent('modal_close', $modal_id);
    }

    public function show_table()
    {
        $this->door_table_visibility  = true;
        $this->door_detail_visibility = false;
    }

    public function show_detail()
    {
        $this->door_table_visibility  = false;
        $this->door_detail_visibility = true;
    }

    public function getDoorDetail($id)
    {
        $door = Door::where('id', $id)->first();

        $this->name       = $door->name;
        $this->device_id  = $door->device_id;
        $this->socket_id  = $door->socket_id;
        $this->is_lock    = $door->is_lock;
        $this->created_at = $door->created_at;

        $this->show_detail();
    }

    public function edit($id)
    {
        $door = Door::where('id', $id)->first();
        return $door;
    }
}
