<?php

namespace App\Http\Livewire;

use App\Models\Door;
use App\Models\Office;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class DoorComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $device_id, $socket_id, $is_lock, $created_at;
    public $edit_id, $name_edited, $device_id_edited;
    public $office_name, $office_id;
    public $door_url;
    public $available_user;
    public $search;

    public $door_table_visibility  = true;
    public $door_detail_visibility = false;

    public function render()
    {
        $office = Office::where('user_id', request()->user()->id)->first();
        $data['doors'] = Door::where('name', 'like', '%' . $this->search . '%')->where('office_id', $office->id)->paginate(7);

        $this->office_name = $office->name;
        $this->office_id   = $office->id;

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

    protected function getMyUrl()
    {
        $url_protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
        $url_server   = $_SERVER['SERVER_NAME'];
        $url_port     = $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '';

        return $url_protocol . $url_server . $url_port;
    }

    public function openModal($modal_id)
    {
        if ($this->door_table_visibility == true) {
            $this->resetData();
        }

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
        $this->resetData();

        $this->door_table_visibility  = true;
        $this->door_detail_visibility = false;
    }

    public function show_detail()
    {
        $this->door_table_visibility  = false;
        $this->door_detail_visibility = true;
    }

    public function storeDoor()
    {
        $this->validate([
            'name' => ['required', 'string', 'min:4', 'max:50'],
        ]);

        $door['name'] = $this->name;
        $door['office_id'] = $this->office_id;

        $status = Door::create($door);

        if ($status) {
            session()->flash('insert_success', $door['name']);
        } else {
            session()->flash('insert_failed', $door['name']);
        }

        $this->resetData();
        $this->closeModal('addDoor');
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

        $this->door_url = $this->getMyUrl() . '/public-access/' . $this->edit_id;

        $this->show_detail();
    }

    public function edit()
    {
        $this->resetModal();
        $this->name_edited = $this->name;
        $this->openModal('editDoor');
    }

    public function updateDoor()
    {
        $this->validate([
            'name_edited' => ['required', 'string', 'min:4', 'max:50'],
        ]);

        $door = Door::where('id', $this->edit_id)->first();
        $door->name = $this->name_edited;

        $status = $door->save();

        if ($status) {
            $this->name = $this->name_edited;
            session()->flash('update_success', $this->name);
        } else {
            session()->flash('update_failed', $this->name);
        }

        $this->closeModal('editDoor');
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

    public function addAccess()
    {
        $this->available_user = '';
    }
}
