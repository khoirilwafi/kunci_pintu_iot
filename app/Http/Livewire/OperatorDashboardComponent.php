<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Door;
use App\Models\Office;
use App\Models\Scedule;
use Livewire\Component;

class OperatorDashboardComponent extends Component
{
    public $office_id;
    public $connection_status = 'Menghubungkan ...';
    public $connection_color = 'yellow';

    protected $listeners = ['socketEvent' => 'socketEvent', 'doorStatusEvent' => 'doorStatusEvent'];

    protected function getDayName($day)
    {
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        return $days[$day];
    }

    public function render()
    {
        $office = Office::where('user_id', request()->user()->id)->first();
        $this->office_id = $office->id;

        $data['pintu'] = Door::where('office_id', $this->office_id)->whereNotNull('device_id')->count();
        $data['pintu_online'] = Door::where('office_id', $this->office_id)->whereNotNull('socket_id')->count();
        $data['pintu_terbuka'] = Door::where('office_id', $this->office_id)->where('is_lock', 0)->count();


        $date_now = Carbon::today();
        $day_now = $this->getDayName(Carbon::now()->format('1'));

        $data['jadwal_hari_ini'] = Scedule::where('office_id', $this->office_id)->whereDate('date_begin', '=', $date_now)->orWhereRaw('FIND_IN_SET(?, day_repeating) > 0', $day_now)->orderBy('time_begin', 'asc')->get();

        return view('livewire.operator-dashboard-component', $data);
    }

    public function doorStatusEvent()
    {
        $this->resetPage();
    }

    public function socketEvent($data)
    {
        $this->connection_status = $data['text'];
        $this->connection_color = $data['color'];
    }
}
