<?php

namespace App\Http\Livewire;

use App\Models\Office;
use Livewire\Component;
use App\Models\AccessLog;
use Livewire\WithPagination;

class AccessHistoryComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        // get operator office
        $office = Office::where('user_id', request()->user()->id)->first();

        // get histories
        $data['histories'] = AccessLog::with(['door', 'user', 'office'])->whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->where('office_id', $office->id)->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.access-history-component', $data);
    }
}
