<?php

namespace App\Http\Livewire;

use App\Models\Avatar;
use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OperatorComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // data model
    public $name, $email, $gender;
    public $delete_id;
    public $search = '';

    // render
    public function render()
    {
        // get all operator and paginate
        $data['operators'] = User::with('office')->where('name', 'like', '%' . $this->search . '%')->where('role', 'operator')->paginate(7);

        return view('livewire.operator-component', $data);
    }

    // reset paginate
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // reset modal (field and error message)
    public function resetModal()
    {
        $this->name = '';
        $this->email = '';
        $this->gender = '';

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

    // store new operator
    public function storeOperator(Request $request)
    {
        $this->validate([
            'name'   => ['required', 'min:4', 'unique:users,name'],
            'email'  => ['required', 'email:dns', 'unique:users,email'],
            'gender' => ['required'],
        ]);

        // add data to object
        $operator['name']     = $this->name;
        $operator['email']    = $this->email;
        $operator['gender']   = $this->gender;
        $operator['password'] = Hash::make($this->email);
        $operator['role']     = 'operator';
        $operator['added_by'] = $request->user()->id;

        // kirimkan email disini !!!!!

        // insert to databse
        $insert = User::create($operator);

        // close formulir
        $this->dispatchBrowserEvent('modal_close', 'addOperator');

        if ($insert) {
            session()->flash('insert_success', $this->name);
        } else {
            session()->flash('insert_failed', $this->name);
        }
    }

    // delete confirmation
    public function deleteOperatorConfirm($id)
    {
        // get name of operator
        $data = User::select('name')->where('id', $id)->first();

        // set delete attibute
        $this->delete_id = $id;
        $this->name = $data->name;

        // open modal confirmation
        $this->dispatchBrowserEvent('modal_open', 'deleteConfirm');
    }

    // delete action
    public function delete()
    {
        $avatar = Avatar::where('user_id', $this->delete_id)->first();
        if ($avatar) {
            Storage::disk('local')->delete($avatar->file);
        }

        try {
            // delete operator
            User::where('id', $this->delete_id)->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            session()->flash('delete_failed', $this->name);
        }

        // close confirmation
        $this->dispatchBrowserEvent('modal_close', 'deleteConfirm');

        // reset delete attibute
        $this->name = '';
        $this->delete_id = null;
    }
}
