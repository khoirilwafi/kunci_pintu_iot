<?php

namespace App\Http\Livewire;

use App\Models\Door;
use App\Models\Office;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use PhpParser\Node\Stmt\TryCatch;

class DoorComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $device_id, $socket_id, $is_lock, $created_at;
    public $edit_id, $name_edited, $device_id_edited;
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

    protected function resetData()
    {
        $this->name       = '';
        $this->device_id  = '';
        $this->socket_id  = '';
        $this->is_lock    = '';
        $this->created_at = '';
    }

    protected function resetModal()
    {
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

        $this->edit_id    = $door->id;
        $this->name       = $door->name;
        $this->device_id  = $door->device_id;
        $this->socket_id  = $door->socket_id;
        $this->is_lock    = $door->is_lock;
        $this->created_at = $door->created_at;

        $this->show_detail();
    }

    public function edit()
    {
        $this->resetModal();

        $this->name_edited       = $this->name;
        $this->device_id_edited  = $this->device_id;

        $this->dispatchBrowserEvent('modal_open', 'editDoor');
    }

    public function updateDoor()
    {
        if ($this->device_id_edited != $this->device_id) {
            dd('berubah');
        } else {
            $door = Door::where('id', $this->edit_id)->first();
            $door->name = $this->name_edited;

            $status = $door->save();

            if ($status) {
                $this->name = $this->name_edited;
                session()->flash('update_success', $this->name);
            } else {
                session()->flash('update_failed', $this->name);
            }
        }

        $this->dispatchBrowserEvent('modal_close', 'editDoor');
    }

    public function delete()
    {
        try {
            Door::where('id', $this->edit_id)->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            session()->flash('delete_failed', $this->name);
        }

        $this->resetData();
        $this->show_table();
        $this->closeModal('deleteConfirm');
    }
}
