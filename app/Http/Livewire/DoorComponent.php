<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Door;
use App\Models\User;
use App\Models\Access;
use App\Models\Office;
use App\Logs\CustomLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Events\DoorCommandEvent;
use App\Events\DoorUnlinkEvent;
use Illuminate\Support\Facades\Log;

class DoorComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $device_name, $socket_id, $is_lock, $created_at;
    public $edit_id, $name_edited, $device_id_edited;
    public $office_name, $office_id;
    public $access_user_id, $access_office_id, $access_is_temporary, $access_date_begin, $access_date_end, $access_time_begin, $access_time_end, $access_status;

    public $access_delete_id, $access_user_name, $access_door_name;
    public $door_name, $door_url, $door_detail_id;
    public $available_user;
    public $search, $searchAccess;
    public $alert_message;

    public $door_table_visibility  = true;
    public $door_detail_visibility = false;
    public $date_visibility = false;

    public $connection_status = 'Menghubungkan ...';
    public $connection_color = 'yellow';

    protected $listeners = ['socketEvent', 'doorStatusEvent', 'doorAlertEvent'];

    public function render()
    {
        // get door at office
        $office        = Office::where('user_id', request()->user()->id)->first();
        $data['doors'] = Door::where('name', 'like', '%' . $this->search . '%')->where('office_id', $office->id)->orderBy('name', 'asc')->get();

        // get user available
        $sub_query     = Access::select('user_id')->where('door_id', $this->edit_id)->get();
        $data['users'] = User::where('added_by', request()->user()->id)->whereNotIn('id', $sub_query)->get();

        // get access list
        $data['access'] = Access::with('user')->whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->searchAccess . '%');
        })->where('door_id', $this->edit_id)->paginate(5);

        $this->office_name = $office->name;
        $this->office_id   = $office->id;

        return view('livewire.door-component', $data);
    }

    public function doorStatusEvent()
    {
        if ($this->door_detail_visibility == true) {
            $this->getDoorDetail($this->door_detail_id);
        }
    }

    public function socketEvent($data)
    {
        $this->connection_status = $data['text'];
        $this->connection_color = $data['color'];
    }

    public function doorAlertEvent($data)
    {
        $this->door_name = $data['name'];
        $this->alert_message = $data['message'];
        $this->dispatchBrowserEvent('modal_open', 'alertModal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchAccess()
    {
        $this->resetPage();
    }

    protected function resetData()
    {
        $this->name       = '';
        $this->device_name  = '';
        $this->socket_id  = '';
        $this->is_lock    = '';
        $this->created_at = '';
    }

    protected function resetAccessInput()
    {
        $this->access_user_id      = '';
        $this->access_office_id    = '';
        $this->access_is_temporary = '';
        $this->access_date_begin   = '';
        $this->access_date_end     = '';
        $this->access_time_begin   = '';
        $this->access_time_end     = '';
        $this->access_status       = '';

        $this->date_visibility = false;
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
        $this->resetAccessInput();
        $this->dispatchBrowserEvent('modal_open', $modal_id);
    }

    public function closeModal($modal_id)
    {
        $this->resetModal();
        $this->resetAccessInput();
        $this->dispatchBrowserEvent('modal_close', $modal_id);
    }

    public function show_table()
    {
        $this->searchAccess = '';
        $this->resetData();

        $this->door_table_visibility  = true;
        $this->door_detail_visibility = false;
    }

    public function show_detail()
    {
        $this->search = '';

        $this->door_table_visibility  = false;
        $this->door_detail_visibility = true;
    }

    public function storeDoor()
    {
        $this->validate([
            'name' => [
                'required', 'string', 'min:4', 'max:50',
                Rule::unique('doors')->where(function ($query) {
                    return $query->where('office_id', $this->office_id);
                })
            ],
        ]);

        $door['name'] = $this->name;
        $door['office_id'] = $this->office_id;

        try {
            // insert door
            Door::create($door);

            // notification
            session()->flash('insert_success', $door['name']);
            Log::info('add new door', ['door' => $door]);
        } catch (Exception $e) {

            // notification
            session()->flash('insert_failed', $door['name']);
            Log::error('add new door failed', ['door' => $door, 'error' => $e]);
        }

        $this->resetData();
        $this->closeModal('addDoor');
    }

    public function getDoorDetail($id)
    {
        $door = Door::where('id', $id)->first();

        $this->edit_id     = $door->id;
        $this->name        = $door->name;
        $this->device_name = $door->device_name;
        $this->socket_id   = $door->socket_id;
        $this->is_lock     = $door->is_lock;
        $this->created_at  = $door->created_at;
        $this->door_url    = $this->edit_id;

        $this->door_detail_id = $id;
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
        // $this->validate([
        //     'name_edited' => ['required', 'string', 'min:4', 'max:50', Rule::unique('users', 'name')->ignore($this->edit_id)],
        // ]);

        $this->validate([
            'name_edited' => [
                'required', 'string', 'min:4', 'max:50',
                Rule::unique('doors', 'name')->where(function ($query) {
                    return $query->where('office_id', $this->office_id);
                })->ignore($this->edit_id)
            ],
        ]);


        $door = Door::where('id', $this->edit_id)->first();

        try {
            // update
            $door->name = $this->name_edited;
            $door->save();

            // notification
            session()->flash('update_success', $this->name);
            Log::info('change door detail', ['door' => $door]);
        } catch (Exception $e) {

            // notification
            session()->flash('update_failed', $this->name);
            Log::error('change door detail failed', ['door' => $door, 'error' => $e]);
        }

        $this->getDoorDetail($this->edit_id);
        $this->closeModal('editDoor');
    }

    public function delete()
    {
        try {
            $door = Door::where('id', $this->edit_id)->first();
            Log::info('delete door', ['door' => $door]);
            $door->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            session()->flash('delete_failed', $this->name);
            Log::error('delete door failed', ['door' => $door, 'error' => $e]);
        }

        $this->resetData();
        $this->show_table();
        $this->closeModal('deleteConfirm');
    }

    public function showDate()
    {
        $this->reset(['access_date_begin', 'access_date_end']);

        if ($this->date_visibility) {
            $this->date_visibility = false;
        } else {
            $this->date_visibility = true;
        }
    }

    public function storeAccess()
    {
        $this->validate([
            'access_user_id'    => ['required'],
            'access_time_begin' => ['required', 'date_format:H:i'],
            'access_time_end'   => ['required', 'date_format:H:i'],
            'access_date_begin' => ['required_if:access_is_temporary,1', 'date'],
            'access_date_end'   => ['required_if:access_is_temporary,1', 'date', 'after_or_equal:access_date_begin'],
        ]);

        $access = array(
            'user_id'      => $this->access_user_id,
            'door_id'      => $this->edit_id,
            'time_begin'   => $this->access_time_begin,
            'time_end'     => $this->access_time_end,
            'date_begin'   => $this->access_date_begin ?  $this->access_date_begin : null,
            'date_end'     => $this->access_date_end ?  $this->access_date_end : null,
            'is_temporary' => $this->access_is_temporary ? 1 : 0,
            'is_running'   => 1,
        );

        try {
            // insert
            Access::create($access);

            // notificarion
            session()->flash('insert_success', 'Akses Pengguna');
            Log::info('add new user access', ['access' => $access]);
        } catch (Exception $e) {
            session()->flash('insert_failed', 'Akses Pengguna');
            Log::info('add new user access failed', ['access' => $access, 'error' => $e]);
        }

        $this->closeModal('addAccess');
    }

    public function confirmDeleteAccess($id)
    {
        // get access data
        $data = Access::with(['user', 'door'])->where('id', $id)->first();

        $this->access_delete_id = $id;
        $this->access_user_name = $data->user->name;
        $this->access_door_name = $data->door->name;

        $this->openModal('deleteAccessConfirm');
    }

    public function deleteAccess()
    {
        try {
            $access = Access::where('id', $this->access_delete_id)->first();
            Log::info('delete user access', ['access' => $access]);
            $access->delete();
            session()->flash('delete_success', $this->access_user_name);
        } catch (Exception $e) {
            Log::error('delete user access failed', ['access' => $access, 'error' => $e]);
            session()->flash('delete_failed', $this->access_user_name);
        }

        $this->closeModal('deleteAccessConfirm');
    }

    public function changeAccess($id)
    {
        $access = Access::with('user')->where('id', $id)->first();

        if ($access->is_running == 0) {
            $access->is_running = 1;
        } else {
            $access->is_running = 0;
        }

        try {
            $access->save();
            Log::info('change user access', ['access' => $access]);
        } catch (Exception $e) {
            Log::error('change user access failed', ['access' => $access, 'error' => $e]);
        }
    }

    public function changeLocking($id, $status, $key)
    {
        // broadcast event
        event(new DoorCommandEvent($this->office_id, request()->user()->id, $id, $status, $key));

        // save log
        new CustomLog(request()->user()->id, $id, $this->office_id, 'remote akses');
    }

    public function unlink()
    {
        $door = Door::where('id', $this->edit_id)->first();

        try {
            // broadcast event
            event(new DoorUnlinkEvent($door->office_id, $door->id, $door->key));

            $door->device_name = null;
            $door->socket_id = null;
            $door->device_pass = null;
            $door->key = null;

            // update
            $door->save();

            // notification
            session()->flash('update_success', $this->name);
            Log::info('unlink door device', ['door' => $door]);
        } catch (Exception $e) {
            session()->flash('update_failed', $this->name);
            Log::error('unlink door device failed', ['door' => $door, 'error' => $e]);
        }

        $this->getDoorDetail($this->edit_id);
        $this->closeModal('unlinkDoor');
    }

    public function printPoster()
    {
        $url = '/poster/' . $this->edit_id;
        $js = "window.open('{$url}', '_blank');";
        $this->dispatchBrowserEvent('open_tab', ['js' => $js]);
    }
}
