<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Door;
use App\Models\Office;
use App\Logs\CustomLog;
use App\Models\Schedule;
use Livewire\Component;
use App\Models\SchedulePivot;
use Livewire\WithPagination;
use App\Events\DoorCommandEvent;
use App\Events\DoorScheduleEvent;
use Illuminate\Support\Facades\Log;

class ScheduleComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $office_id, $schedule_id, $delete_name, $delete_id, $schedule_door_id;

    public $insert_name, $insert_date_begin, $insert_date_end, $insert_time_begin, $insert_time_end, $insert_is_repeat, $insert_status;
    public $insert_day_0, $insert_day_1, $insert_day_2, $insert_day_3, $insert_day_4, $insert_day_5, $insert_day_6;

    public $edit_name, $edit_date_begin, $edit_date_end, $edit_time_begin, $edit_time_end;
    public $edit_day_0, $edit_day_1, $edit_day_2, $edit_day_3, $edit_day_4, $edit_day_5, $edit_day_6;

    public $schedule_table_visibility = true;
    public $schedule_detail_visibility = false;

    public $connection_status = 'Menghubungkan ...';
    public $connection_color = 'yellow';
    public $search, $day_repeating;
    public $door_name;

    protected $listeners = ['socketEvent', 'doorStatusEvent', 'doorAlertEvent'];


    public function render()
    {
        // get operator office
        $office = Office::where('user_id', request()->user()->id)->first();
        $this->office_id = $office->id;

        // get all schedule
        $data['schedules'] = Schedule::where('office_id', $this->office_id)->where('name', 'like', '%' . $this->search . '%')->get();

        // get door available
        $query = SchedulePivot::select('door_id')->where('schedule_id', $this->schedule_id)->get();
        $data['doors'] = Door::where('office_id', $this->office_id)->whereNotIn('id', $query)->get();

        // get door linked
        $data['door_links'] = SchedulePivot::with('door')->where('schedule_id', $this->schedule_id)->get();

        return view('livewire.schedule-component', $data);
    }

    public function doorStatusEvent()
    {
        if ($this->schedule_detail_visibility == true) {
            $this->getSceduleDetail($this->schedule_id);
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
        $this->dispatchBrowserEvent('modal_open', 'alertModal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function resetModal()
    {
        $this->reset(['insert_name', 'insert_date_begin', 'insert_date_end', 'insert_time_begin', 'insert_time_end', 'insert_is_repeat']);
        $this->reset(['insert_day_0', 'insert_day_1', 'insert_day_2', 'insert_day_3', 'insert_day_4', 'insert_day_5', 'insert_day_6']);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function showTable()
    {
        $this->schedule_table_visibility = true;
        $this->schedule_detail_visibility = false;
    }

    public function showDetail()
    {
        $this->search = '';

        $this->schedule_table_visibility = false;
        $this->schedule_detail_visibility = true;
    }

    public function openModal($modal_id)
    {
        if ($this->schedule_table_visibility == true) {
            $this->resetModal();
        }

        $this->dispatchBrowserEvent('modal_open', $modal_id);
    }

    public function closeModal($modal_id)
    {
        if ($this->schedule_table_visibility == true) {
            $this->resetModal();
        }

        $this->dispatchBrowserEvent('modal_close', $modal_id);
    }

    public function storeScedule()
    {
        $this->validate([
            'insert_name'       => ['required', 'string', 'min:5', 'max:100'],
            'insert_date_begin' => ['required', 'date'],
            'insert_date_end'   => ['required', 'date', 'after_or_equal:insert_date_begin'],
            'insert_time_begin' => ['required', 'date_format:H:i'],
            'insert_time_end'   => ['required', 'date_format:H:i'],
        ]);

        $schedule['office_id']     = $this->office_id;
        $schedule['name']          = $this->insert_name;
        $schedule['date_begin']    = $this->insert_date_begin;
        $schedule['date_end']      = $this->insert_date_end;
        $schedule['time_begin']    = $this->insert_time_begin;
        $schedule['time_end']      = $this->insert_time_end;
        $schedule['is_repeating']  = 0;
        $schedule['day_repeating'] = '';

        $day = [$this->insert_day_0, $this->insert_day_1, $this->insert_day_2, $this->insert_day_3, $this->insert_day_4, $this->insert_day_5, $this->insert_day_6];
        $day_repeating = '';

        for ($i = 0; $i < 7; $i++) {
            if ($day[$i] != null) {
                $schedule['is_repeating'] = 1;
                $day_repeating .= $day[$i] . ',';
            } else {
                $day_repeating .= ',';
            }
        }

        $schedule['day_repeating'] = $day_repeating == '' ? $day_repeating : substr($day_repeating, 0, -1);

        try {
            Schedule::create($schedule);
            session()->flash('insert_success', $schedule['name']);
            Log::info('add new schedule', ['schedule' => $schedule]);
        } catch (Exception $e) {
            session()->flash('insert_failed', $schedule['name']);
            Log::error('add new schedule failed', ['schedule' => $schedule, 'error' => $e]);
        }

        $this->closeModal('addScedule');
    }

    public function deleteConfirm($id)
    {
        if ($this->schedule_table_visibility == true) {
            $schedule = Schedule::where('id', $id)->first();
            $this->delete_name = $schedule->name;
            $this->delete_id   = $schedule->id;
        } else {
            $doors = SchedulePivot::with('door')->where('id', $id)->first();
            $this->delete_name = $doors->door->name;
            $this->delete_id   = $doors->door->id;
        }

        $this->openModal('deleteConfirm');
    }

    public function delete()
    {
        try {
            if ($this->schedule_table_visibility == true) {
                $schedule = Schedule::where('id', $this->delete_id)->first();
                Log::info('delete schedule', ['schedule' => $schedule]);
                $schedule->delete();
            } else {
                $door = SchedulePivot::where('schedule_id', $this->schedule_id)->where('door_id', $this->delete_id)->first();
                Log::info('delete door in schedule', ['door' => $door]);
                $door->delete();
            }
            session()->flash('delete_success', $this->delete_name);
        } catch (Exception $e) {
            Log::error('delete schedule failed', ['error' => $e]);
            session()->flash('delete_failed', $this->delete_name);
        }

        $this->closeModal('deleteConfirm');
    }

    public function getSceduleDetail($id)
    {
        $schedule = Schedule::where('id', $id)->first();

        $this->schedule_id = $schedule->id;

        $this->insert_name       = $schedule->name;
        $this->insert_date_begin = $schedule->date_begin;
        $this->insert_date_end   = $schedule->date_end;
        $this->insert_time_begin = $schedule->time_begin;
        $this->insert_time_end   = $schedule->time_end;
        $this->insert_is_repeat  = $schedule->is_repeating;

        $this->insert_status = $schedule->status;

        $days = explode(',', $schedule->day_repeating);
        $day_repeating = '';


        foreach ($days as $day) {
            if ($day != '') $day_repeating .= ucfirst($day) . ', ';
        }

        $this->day_repeating = substr($day_repeating, 0, -2);
        $this->showDetail();
    }

    public function edit()
    {
        $schedule = Schedule::where('id', $this->schedule_id)->first();

        $this->edit_name       = $schedule->name;
        $this->edit_date_begin = $schedule->date_begin;
        $this->edit_date_end   = $schedule->date_end;
        $this->edit_time_begin = substr($schedule->time_begin, 0, -3);
        $this->edit_time_end   = substr($schedule->time_end, 0, -3);

        $days = explode(',', $schedule->day_repeating);

        $this->edit_day_0 = $days[0];
        $this->edit_day_1 = $days[1];
        $this->edit_day_2 = $days[2];
        $this->edit_day_3 = $days[3];
        $this->edit_day_4 = $days[4];
        $this->edit_day_5 = $days[5];
        $this->edit_day_6 = $days[6];

        $this->openModal('editScedule');
    }

    public function updateScedule()
    {
        $this->validate([
            'edit_name'       => ['required', 'string', 'min:5', 'max:100'],
            'edit_date_begin' => ['required', 'date'],
            'edit_date_end'   => ['required', 'date'],
            'edit_time_begin' => ['required', 'date_format:H:i'],
            'edit_time_end'   => ['required', 'date_format:H:i'],
        ]);

        $schedule = Schedule::where('id', $this->schedule_id)->first();

        $schedule->name       = $this->edit_name;
        $schedule->date_begin = $this->edit_date_begin;
        $schedule->date_end   = $this->edit_date_end;
        $schedule->time_begin = $this->edit_time_begin;
        $schedule->time_end   = $this->edit_time_end;

        $schedule->is_repeating = 0;
        $schedule->day_repeating = '';

        $day = [$this->edit_day_0, $this->edit_day_1, $this->edit_day_2, $this->edit_day_3, $this->edit_day_4, $this->edit_day_5, $this->edit_day_6];
        $day_repeating = '';

        for ($i = 0; $i < 7; $i++) {
            if ($day[$i] != null) {
                $schedule['is_repeating'] = 1;
                $day_repeating .= $day[$i] . ',';
            } else {
                $day_repeating .= ',';
            }
        }

        $schedule->day_repeating = $day_repeating == '' ? $day_repeating : substr($day_repeating, 0, -1);

        try {
            $schedule->save();
            session()->flash('update_success', $this->edit_name);
            Log::info('update schedule', ['schedule' => $schedule]);
        } catch (Exception $e) {
            session()->flash('update_failed', $this->edit_name);
            Log::error('update schedule failed', ['schedule' => $schedule, 'error' => $e]);
        }

        $this->getSceduleDetail($this->schedule_id);
        $this->closeModal('editScedule');
    }

    public function storeDoor()
    {
        $this->validate([
            'schedule_door_id' => ['required', 'string'],
        ]);

        $pivot['door_id']    = $this->schedule_door_id;
        $pivot['schedule_id'] = $this->schedule_id;

        $door = Door::where('id', $pivot['door_id'])->first();

        try {
            SchedulePivot::create($pivot);
            session()->flash('insert_success', $door->name);
            Log::info('add door to schedule', ['door' => $door, 'pivot' => $pivot]);
        } catch (Exception $e) {
            session()->flash('insert_failed', $door->name);
            Log::error('add door to schedule failed', ['door' => $door, 'pivot' => $pivot, 'error' => $e]);
        }

        $this->closeModal('addDoor');
    }

    public function scheduleStop()
    {
        $schedule = Schedule::with('door')->where('id', $this->schedule_id)->first();

        foreach ($schedule->door as $door) {

            // broadcast event
            event(new DoorScheduleEvent($schedule->office_id, request()->user()->id, $door->id, $schedule->time_end, 'stop', $door->token));

            // save log
            new CustomLog(request()->user()->id, $door->id, $this->office_id, 'membatalkan jadwal');
        }

        $schedule->status = 'done';
        $schedule->save();

        $this->getSceduleDetail($schedule->id);
    }

    public function changeLocking($id, $status, $token)
    {
        // broadcast event
        event(new DoorCommandEvent($this->office_id, request()->user()->id, $id, $status, $token));

        // save log
        new CustomLog(request()->user()->id, $id, $this->office_id, 'remote akses');
    }
}
