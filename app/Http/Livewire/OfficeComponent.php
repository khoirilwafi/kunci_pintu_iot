<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Office;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class OfficeComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // data model
    public $name, $user_id, $operator_name, $delete_id, $update_id, $office_id;

    // render
    public function render()
    {
        // get office and its operator
        $data['offices'] = Office::with('user')->paginate(7);

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
        $this->validate([
            'name'    => ['required', 'min:4', 'unique:offices,name'],
            'user_id' => ['required'],
        ]);

        $office['name']    = $this->name;
        $office['user_id'] = $this->user_id;

        $insert = Office::create($office);

        if ($insert) {
            session()->flash('insert_success', $this->name);
        } else {
            session()->flash('insert_failed', $this->name);
        }

        // close formulir
        $this->dispatchBrowserEvent('modal_close', 'addOffice');
        session()->flash('insert_success', $this->name);
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
        $this->validate([
            'name'    => ['required', Rule::unique('offices')->ignore($this->office_id)],
            'user_id' => ['required'],
        ]);

        $office = Office::where('id', $this->update_id)->first();

        $office->name    = $this->name;
        $office->user_id = $this->user_id;

        $update = $office->save();
        $this->dispatchBrowserEvent('modal_close', 'editOffice');

        if ($update) {
            session()->flash('update_success', $this->name);
        } else {
            session()->flash('update_failed', $this->name);
        }

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
            // delete operator
            Office::where('id', $this->delete_id)->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            session()->flash('delete_failed', $this->name);
        }

        // close confirmation
        $this->dispatchBrowserEvent('modal_close', 'deleteConfirm');

        // reset delete attibute
        $this->name = '';
        $this->delete_id = '';
    }
}
