<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Random;

class OperatorComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // data model
    public $name, $email, $gender, $phone;
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
        $this->name   = '';
        $this->gender = '';
        $this->email  = '';
        $this->phone  = '';

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
        // validate input
        $this->validate([
            'name'   => ['required', 'min:4', 'unique:users,name'],
            'gender' => ['required'],
            'email'  => ['required', 'email', 'unique:users,email'],
            'phone'  => ['required', 'numeric', 'unique:users,phone', 'digits_between:11,13'],
        ]);

        // generate random password
        $password = Random::generate(15);

        // prepare data
        $operator = new User();

        $operator->name     = $this->name;
        $operator->gender   = $this->gender;
        $operator->email    = $this->email;
        $operator->phone    = $this->phone;
        $operator->role     = 'operator';
        $operator->added_by = $request->user()->id;

        // add password
        $operator->forceFill(['password' => Hash::make($password)]);

        try {
            // insert to databse
            $operator->save();

            // email notification
            $operator->notify(new NewUserNotification($password));

            // notification
            session()->flash('insert_success', $this->name);
            Log::info('add new operator', ['operator' => $operator]);
        } catch (Exception $e) {

            // error notification
            session()->flash('insert_failed', $this->name);
            Log::error('add new operator failed', ['operator' => $operator, 'error' => $e]);
        }

        // close formulir
        $this->dispatchBrowserEvent('modal_close', 'addOperator');
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
        try {
            // delete operator
            $operator = User::where('id', $this->delete_id)->first();

            // delete avatar
            if ($operator->avatar != null) {
                Storage::disk('local')->delete('/images/' . $operator->avatar);
            }

            Log::info('delete operator', ['operator' => $operator]);
            $operator->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            Log::error('delete operator failed', ['operator' => $operator, 'error' => $e]);
            session()->flash('delete_failed', $this->name);
        }

        // close confirmation
        $this->dispatchBrowserEvent('modal_close', 'deleteConfirm');

        // reset delete attibute
        $this->name = '';
        $this->delete_id = null;
    }
}
