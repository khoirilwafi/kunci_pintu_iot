<?php

namespace App\Http\Livewire;

use App\Models\Scedule;
use Livewire\Component;
use Livewire\WithPagination;

class SceduleComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $scedule_table_visibility = true;

    public function render()
    {
        // get all scedule
        $data['scedules'] = Scedule::where('user_id', request()->user()->id)->paginate(7);

        return view('livewire.scedule-component', $data);
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
}
