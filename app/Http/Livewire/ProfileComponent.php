<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Avatar;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileComponent extends Component
{
    use WithFileUploads;

    public $email, $name, $gender, $role;
    public $password, $password_confirmation;
    public $avatar, $last_avatar_temp;
    public $iteration = 0;

    public function render(Request $request)
    {
        $user = User::with('avatar')->where('id', $request->user()->id)->first();
        $data_avatar = new Avatar();

        if ($user->avatar == null) {
            $data_avatar->name = '02943e5368adf6cc72f4a2e0a435090b.png';
        } else {
            $data_avatar->name = $user->avatar->name;
        }

        return view('livewire.profile-component', ['user' => $user, 'data_avatar' => $data_avatar]);
    }

    public function resetInput()
    {
        $this->avatar = null;
        $this->iteration++;

        $this->name     = '';
        $this->email    = '';
        $this->gender   = '';
        $this->role     = '';
        $this->password = '';

        $this->password_confirmation = '';

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModal($modal_id)
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('modal_open', $modal_id);
    }

    public function closeModal($modal_id)
    {
        $this->resetInput();
        $this->dispatchBrowserEvent('modal_close', $modal_id);
    }

    public function storeAvatar(Request $request)
    {
        $this->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $last_avatar = Avatar::where('user_id', $request->user()->id)->first();

        if ($last_avatar != null) {
            Storage::disk('local')->delete($last_avatar->file);
        }

        $name = md5(Carbon::now()) . '.' . $this->avatar->getClientOriginalExtension();
        $file = $this->avatar->storeAs('images', $name);

        $data_avatar = array(
            'user_id' => $request->user()->id,
            'name'    => $name,
            'file'    => $file,
        );

        Avatar::updateOrCreate(['user_id' => $data_avatar['user_id']], $data_avatar);

        $this->closeModal('editAvatar');
        $this->dispatchBrowserEvent('avatar_change', $name);
    }

    public function editProfile(Request $request)
    {
        $user = $request->user();

        $this->resetInput();

        $this->name   = $user->name;
        $this->email  = $user->email;
        $this->gender = $user->gender;
        $this->role   = $user->role;

        $this->dispatchBrowserEvent('modal_open', 'editProfile');
    }

    public function storeProfile(Request $request)
    {
        $user = $request->user();
        $id   = $user->id;

        $this->validate([
            'name'   => ['required', 'min:4', Rule::unique('users')->ignore($id)],
            'email'  => ['required', 'email:dns', Rule::unique('users')->ignore($id)],
            'gender' => ['required'],
            'role'   => ['required'],
        ]);

        $user = User::find($id);

        $user->email  = $this->email;
        $user->name   = $this->name;
        $user->gender = $this->gender;
        $user->role   = $this->role;

        $result = $user->save();

        $this->closeModal('editProfile');
        $this->dispatchBrowserEvent('name_change', $user->name);

        if ($result) {
            session()->flash('update_success', 'Profil Anda');
        } else {
            session()->flash('update_failed', 'Profil Anda');
        }
    }

    public function confirm(Request $request)
    {
        $this->validate([
            'password' => ['required'],
        ]);

        $user = User::find($request->user()->id);

        if (Hash::check($this->password, $user->password)) {
            $this->closeModal('confirmPassword');
            $this->openModal('changePassword');
        } else {
            $this->closeModal('confirmPassword');
            session()->flash('password_failed', 'Password');
        }
    }

    public function storePassword(Request $request)
    {
        $this->validate([
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user = User::find($request->user()->id);
        $user->password = Hash::make($this->password);
        $result = $user->save();

        $this->closeModal('changePassword');

        if ($result) {
            session()->flash('update_success', 'Password');
        } else {
            session()->flash('update_failed', 'Password');
        }
    }
}
