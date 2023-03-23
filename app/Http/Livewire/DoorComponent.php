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

    public $name;
    public $search;

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
}
