<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Door;
use App\Models\Office;
use App\Models\Scedule;
use Livewire\Component;

class OperatorDashboardComponent extends Component
{
    public $office_id, $door_name, $count;
    public $connection_status = 'Menghubungkan ...';
    public $connection_color = 'yellow';

    protected $listeners = ['socketEvent', 'doorStatusEvent', 'doorAlertEvent'];


    public function render()
    {
        $office = Office::where('user_id', request()->user()->id)->first();
        $this->office_id = $office->id;

        $data['pintu'] = Door::where('office_id', $this->office_id)->whereNotNull('device_id')->count();
        $data['pintu_online'] = Door::where('office_id', $this->office_id)->whereNotNull('socket_id')->count();
        $data['pintu_terbuka'] = Door::where('office_id', $this->office_id)->whereNotNull('device_id')->whereNotNull('socket_id')->where('is_lock', 0)->count();

        $now = Carbon::now();

        $date = $now->toDateString();

        $day_week = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $day = $day_week[$now->format('w')];

        $data['jadwal_hari_ini'] = Scedule::where('date_end', '>=', $date)
            ->where(function ($date_query) use ($date, $day) {
                $date_query->where('date_begin', $date)->orWhere(function ($day_query) use ($date, $day) {
                    $day_query->where('date_begin', '<', $date)->where('is_repeating', 1)->whereRaw("find_in_set(?, day_repeating) > 0", [$day]);
                });
            })->get();

        return view('livewire.operator-dashboard-component', $data);
    }

    public function doorStatusEvent()
    {
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
}
