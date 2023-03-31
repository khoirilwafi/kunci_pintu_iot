<?php

namespace App\Http\Livewire;

use App\Models\Door;
use App\Models\Office;
use App\Models\Scedule;
use App\Models\ScedulePivot;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class SceduleComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scedule_id, $delete_name, $delete_id, $scedule_door_id;

    public $insert_name, $insert_date, $insert_time_begin, $insert_time_end, $insert_is_repeat;
    public $insert_day_0, $insert_day_1, $insert_day_2, $insert_day_3, $insert_day_4, $insert_day_5, $insert_day_6;

    public $edit_name, $edit_date, $edit_time_begin, $edit_time_end, $edit_is_repeat;
    public $edit_day_0, $edit_day_1, $edit_day_2, $edit_day_3, $edit_day_4, $edit_day_5, $edit_day_6;

    public $scedule_table_visibility = true;
    public $scedule_detail_visibility = false;
    public $insert_day = false;

    public $search, $searchDoor = 'x';


    public function render()
    {
        // get all scedule
        $data['scedules'] = Scedule::where('user_id', request()->user()->id)->where('name', 'like', '%' . $this->search . '%')->paginate(7);

        // get operator office
        $office = Office::where('user_id', request()->user()->id)->first();

        // get door available
        $query = ScedulePivot::select('door_id')->where('scedule_id', $this->scedule_id)->get();
        $data['doors'] = Door::where('office_id', $office->id)->whereNotIn('id', $query)->get();

        // get door linked
        $data['door_links'] = ScedulePivot::with('door')->where('scedule_id', $this->scedule_id)->paginate(5);

        return view('livewire.scedule-component', $data);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchDoor()
    {
        $this->resetPage();
    }

    protected function resetModal()
    {
        $this->reset(['insert_name', 'insert_date', 'insert_time_begin', 'insert_time_end', 'insert_is_repeat']);
        $this->reset(['insert_day_0', 'insert_day_1', 'insert_day_2', 'insert_day_3', 'insert_day_4', 'insert_day_5', 'insert_day_6']);

        $this->insert_day = false;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function showTable()
    {
        $this->searchDoor = '';

        $this->scedule_table_visibility = true;
        $this->scedule_detail_visibility = false;
    }

    public function showDetail()
    {
        $this->search = '';

        $this->scedule_table_visibility = false;
        $this->scedule_detail_visibility = true;
    }

    public function openModal($modal_id)
    {
        if ($this->scedule_table_visibility == true) {
            $this->resetModal();
        }

        $this->dispatchBrowserEvent('modal_open', $modal_id);
    }

    public function closeModal($modal_id)
    {
        if ($this->scedule_table_visibility == true) {
            $this->resetModal();
        }

        $this->dispatchBrowserEvent('modal_close', $modal_id);
    }

    public function storeScedule()
    {
        $this->validate([
            'insert_name'       => ['required', 'string', 'min:5', 'max:100'],
            'insert_date'       => ['required', 'date'],
            'insert_time_begin' => ['required', 'date_format:H:i'],
            'insert_time_end'   => ['required', 'date_format:H:i'],
        ]);

        $scedule['user_id']      = request()->user()->id;
        $scedule['name']         = $this->insert_name;
        $scedule['date_running'] = $this->insert_date;
        $scedule['time_begin']   = $this->insert_time_begin;
        $scedule['time_end']     = $this->insert_time_end;
        $scedule['is_repeating'] = $this->insert_is_repeat;

        $scedule['day_0'] = $this->insert_day_0 != null ? 1 : 0;
        $scedule['day_1'] = $this->insert_day_1 != null ? 1 : 0;
        $scedule['day_2'] = $this->insert_day_2 != null ? 1 : 0;
        $scedule['day_3'] = $this->insert_day_3 != null ? 1 : 0;
        $scedule['day_4'] = $this->insert_day_4 != null ? 1 : 0;
        $scedule['day_5'] = $this->insert_day_5 != null ? 1 : 0;
        $scedule['day_6'] = $this->insert_day_6 != null ? 1 : 0;

        $status = Scedule::create($scedule);

        if ($status) {
            session()->flash('insert_success', $scedule['name']);
        } else {
            session()->flash('insert_failed', $scedule['name']);
        }

        $this->closeModal('addScedule');
    }

    public function addDay()
    {
        if ($this->insert_is_repeat != null) {
            $this->insert_day = true;
        } else {
            $this->reset(['insert_day_0', 'insert_day_1', 'insert_day_2', 'insert_day_3', 'insert_day_4', 'insert_day_5', 'insert_day_6']);
            $this->insert_day = false;
        }
    }

    public function editDay()
    {
        if ($this->edit_is_repeat != null) {
            $this->insert_day = true;
        } else {
            $this->reset(['edit_day_0', 'edit_day_1', 'edit_day_2', 'edit_day_3', 'edit_day_4', 'edit_day_5', 'edit_day_6']);
            $this->insert_day = false;
        }
    }

    public function deleteConfirm($id)
    {
        if ($this->scedule_table_visibility == true) {
            $scedule = Scedule::where('id', $id)->first();

            $this->delete_name = $scedule->name;
            $this->delete_id   = $scedule->id;
        } else {
            $doors = ScedulePivot::with('door')->where('id', $id)->first();

            $this->delete_name = $doors->door->name;
            $this->delete_id   = $doors->id;
        }

        $this->openModal('deleteConfirm');
    }

    public function delete()
    {
        try {

            if ($this->scedule_table_visibility == true) {
                Scedule::where('id', $this->delete_id)->delete();
            } else {
                ScedulePivot::where('id', $this->delete_id)->delete();
            }

            session()->flash('delete_success', $this->delete_name);
        } catch (Exception $e) {
            session()->flash('delete_failed', $this->delete_name);
        }

        $this->closeModal('deleteConfirm');
    }

    public function getSceduleDetail($id)
    {
        $scedule = Scedule::where('id', $id)->first();

        $this->scedule_id = $scedule->id;

        $this->insert_name       = $scedule->name;
        $this->insert_date       = $scedule->date_running;
        $this->insert_time_begin = $scedule->time_begin;
        $this->insert_time_end   = $scedule->time_end;
        $this->insert_is_repeat  = $scedule->is_repeating;

        $this->insert_day_0 = $scedule->day_0;
        $this->insert_day_1 = $scedule->day_1;
        $this->insert_day_2 = $scedule->day_2;
        $this->insert_day_3 = $scedule->day_3;
        $this->insert_day_4 = $scedule->day_4;
        $this->insert_day_5 = $scedule->day_5;
        $this->insert_day_6 = $scedule->day_6;

        $this->showDetail();
    }

    public function edit()
    {
        $scedule = Scedule::where('id', $this->scedule_id)->first();

        $this->edit_name       = $scedule->name;
        $this->edit_date       = $scedule->date_running;
        $this->edit_time_begin = substr($scedule->time_begin, 0, -3);
        $this->edit_time_end   = substr($scedule->time_end, 0, -3);
        $this->edit_is_repeat  = $scedule->is_repeating;

        $this->edit_day_0 = $scedule->day_0;
        $this->edit_day_1 = $scedule->day_1;
        $this->edit_day_2 = $scedule->day_2;
        $this->edit_day_3 = $scedule->day_3;
        $this->edit_day_4 = $scedule->day_4;
        $this->edit_day_5 = $scedule->day_5;
        $this->edit_day_6 = $scedule->day_6;

        if ($this->edit_is_repeat == 1) {
            $this->editDay();
        }

        $this->openModal('editScedule');
    }

    public function updateScedule()
    {
        $this->validate([
            'edit_name'       => ['required', 'string', 'min:5', 'max:100'],
            'edit_date'       => ['required', 'date'],
            'edit_time_begin' => ['required', 'date_format:H:i'],
            'edit_time_end'   => ['required', 'date_format:H:i'],
        ]);

        $scedule = Scedule::where('id', $this->scedule_id)->first();

        $scedule->name         = $this->edit_name;
        $scedule->date_running = $this->edit_date;
        $scedule->time_begin   = $this->edit_time_begin;
        $scedule->time_end     = $this->edit_time_end;
        $scedule->is_repeating = $this->edit_is_repeat;

        $scedule->day_0 = $this->edit_day_0 != null ? 1 : 0;
        $scedule->day_1 = $this->edit_day_1 != null ? 1 : 0;
        $scedule->day_2 = $this->edit_day_2 != null ? 1 : 0;
        $scedule->day_3 = $this->edit_day_3 != null ? 1 : 0;
        $scedule->day_4 = $this->edit_day_4 != null ? 1 : 0;
        $scedule->day_5 = $this->edit_day_5 != null ? 1 : 0;
        $scedule->day_6 = $this->edit_day_6 != null ? 1 : 0;

        $status = $scedule->save();

        if ($status) {
            session()->flash('update_success', $this->edit_name);
        } else {
            session()->flash('update_failed', $this->edit_name);
        }

        $this->getSceduleDetail($this->scedule_id);
        $this->closeModal('editScedule');
    }

    public function storeDoor()
    {
        $this->validate([
            'scedule_door_id' => ['required', 'string'],
        ]);

        $pivot['door_id']    = $this->scedule_door_id;
        $pivot['scedule_id'] = $this->scedule_id;

        $door = Door::where('id', $pivot['door_id'])->first();
        $status = ScedulePivot::create($pivot);

        if ($status) {
            session()->flash('insert_success', $door->name);
        } else {
            session()->flash('insert_failed', $door->name);
        }

        $this->closeModal('addDoor');
    }
}
