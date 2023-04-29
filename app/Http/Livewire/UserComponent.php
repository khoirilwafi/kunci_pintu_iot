<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Access;
use App\Models\Avatar;
use Livewire\Component;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewUserNotification;

class UserComponent extends Component
{
    // pagination theme
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // data model
    public $user_id, $name, $email, $gender, $phone;
    public $delete_id;
    public $search, $searchAccess;

    public $table_visibility = true;
    public $user_detail_visibility = false;

    public $avatar_name, $created_at;


    public function render()
    {
        // get all user and paginate
        $data['users']  = User::where('name', 'like', '%' . $this->search . '%')->where('role', 'pengguna')->where('added_by', request()->user()->id)->paginate(7);

        // get all user access
        $data['access'] = Access::with('door.office')->whereHas('door', function ($query) {
            $query->where('name', 'like', '%' . $this->searchAccess . '%');
        })->where('user_id', $this->user_id)->paginate(5);

        return view('livewire.user-component', $data);
    }

    // reset paginate
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchAccess()
    {
        $this->resetPage();
    }

    // reset modal (field and error message)
    public function resetModal()
    {
        $this->name   = '';
        $this->email  = '';
        $this->gender = '';
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
    public function storeUser(Request $request)
    {
        $this->validate([
            'name'   => ['required', 'min:4', 'unique:users,name'],
            'gender' => ['required'],
            'email'  => ['required', 'email:dns', 'unique:users,email'],
            'phone'  => ['required', 'numeric', 'unique:users,phone', 'digits_between:11,13'],
        ]);

        $password = Random::generate(15);

        // add data to object
        $user['name']     = $this->name;
        $user['email']    = $this->email;
        $user['phone']    = $this->phone;
        $user['gender']   = $this->gender;
        $user['password'] = Hash::make($password);
        $user['role']     = 'pengguna';
        $user['added_by'] = $request->user()->id;

        // insert to databse
        $status = User::create($user);

        // email notification
        $status->notify(new NewUserNotification($password));

        // close formulir
        $this->dispatchBrowserEvent('modal_close', 'addUser');

        if ($status) {
            Log::info('add new user', ['user' => $user]);
            session()->flash('insert_success', $this->name);
        } else {
            session()->flash('insert_failed', $this->name);
        }
    }

    // delete confirmation
    public function deleteUserConfirm($id)
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
            $user = User::where('id', $this->delete_id)->first();
            Log::info('delete user', ['user' => $user]);
            $user->delete();
            session()->flash('delete_success', $this->name);
        } catch (Exception $e) {
            Log::error('delete user failed', ['error' => $e]);
            session()->flash('delete_failed', $this->name);
        }

        // close confirmation
        $this->dispatchBrowserEvent('modal_close', 'deleteConfirm');

        // reset delete attibute
        $this->name = '';
        $this->delete_id = null;
    }

    public function getUserDetail($id)
    {
        $user = User::where('id', $id)->first();
        $avatar = Avatar::where('user_id', $id)->first();

        if ($avatar) {
            $this->avatar_name = $avatar->name;
        } else {
            $this->avatar_name = '02943e5368adf6cc72f4a2e0a435090b.png';
        }

        $this->searchAccess = '';

        $this->user_id    = $id;
        $this->name       = $user->name;
        $this->gender     = $user->gender;
        $this->email      = $user->email;
        $this->phone      = $user->phone;
        $this->created_at = $user->created_at;

        $this->table_visibility = false;
        $this->user_detail_visibility = true;
    }

    public function tampilkan_table()
    {
        $this->search = '';

        $this->table_visibility = true;
        $this->user_detail_visibility = false;
    }
}
