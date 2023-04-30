<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Office;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class OfficeComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // data model
    public $name, $user_id, $operator_name, $delete_id, $update_id, $office_id;
    public $search = '';

    // render
    public function render()
    {
        // get office and its operator
        $data['offices'] = Office::with('user')->where('name', 'like', '%' . $this->search . '%')->paginate(7);

        // get available operator
        $sub_query = Office::select('user_id')->where('user_id', '!=', null);
        $data['available_operators'] = User::where('role', 'operator')->whereNotIn('id', $sub_query)->get();

        return view('livewire.office-component', $data);
    }

    // reset modal (field and error message)
    public function resetModal()
    {
        $this->name = '';
        $this->user_id = '';

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // open modal
    public function openModal($modal_id)
    {
        $this->resetModal();
        $this->dispatchBrowserEvent('modal_open', $modal_id);
    }

    // close modal
    public function closeModal($modal_id)
    {
        $this->resetModal();
        $this->dispatchBrowserEvent('modal_close', $modal_id);
    }

    // store new office
    public function storeOffice()
    {
        // validate input
        $this->validate([
            'name'    => ['required', 'min:4', 'unique:offices,name'],
            'user_id' => ['required'],
        ]);


        try {
            // prepare data
            $office['name']    = $this->name;
            $office['user_id'] = $this->user_id;

            // insert data
            Office::create($office);

            // notification
            session()->flash('insert_success', $this->name);
            Log::info('add new office', ['office' => $office]);
        } catch (Exception $e) {

            // notification
            session()->flash('insert_failed', $this->name);
            Log::error('add new office failed', ['office' => $office, 'error' => $e]);
        }

        // close formulir
        $this->dispatchBrowserEvent('modal_close', 'addOffice');
    }

    // edit office
    public function edit($id)
    {
        $office = Office::with('user')->where('id', $id)->first();

        $this->resetModal();

        $this->name          = $office->name;
        $this->user_id       = $office->user_id;
        $this->operator_name = $office->user->name;
        $this->update_id     = $id;
        $this->office_id     = $office->id;

        $this->dispatchBrowserEvent('modal_open', 'editOffice');
    }

    public function updateOffice()
    {
        // validate input
        $this->validate([
            'name'    => ['required', Rule::unique('offices')->ignore($this->office_id)],
            'user_id' => ['required'],
        ]);

        // get office
        $office = Office::where('id', $this->update_id)->first();

        try {
            // update data
            $office->name    = $this->name;
            $office->user_id = $this->user_id;
            $office->save();

            // notification
            session()->flash('update_success', $this->name);
            Log::info('update office', ['office' => $office]);
        } catch (Exception $e) {

            // notification
            session()->flash('update_failed', $this->name);
            Log::error('update office failed', ['office' => $office, 'error' => $e]);
        }

        $this->dispatchBrowserEvent('modal_close', 'editOffice');

        $this->name          = '';
        $this->user_id       = '';
        $this->operator_name = '';
        $this->update_id     = '';
        $this->office_id     = '';
    }

    // delete confirmation
    public function deleteOfficeConfirm($id)
    {
        // get name of operator
        $data = Office::select('name')->where('id', $id)->first();

        // set delete attibute
        $this->delete_id = $id;
        $this->name = $data->name;

        // open modal confirmation
        $this->dispatchBrowserEvent('modal_open', 'deleteConfirm');
    }

    // delete action
    public function delete()
    {
        try {
            $office = Office::where('id', $this->delete_id)->first();
            Log::info('delete office', ['office' => $office]);
            $office->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            session()->flash('delete_failed', $this->name);
            Log::error('delete office failed', ['office' => $office, 'error' => $e]);
        }

        // close confirmation
        $this->dispatchBrowserEvent('modal_close', 'deleteConfirm');

        // reset delete attibute
        $this->name = '';
        $this->delete_id = '';
    }
}
