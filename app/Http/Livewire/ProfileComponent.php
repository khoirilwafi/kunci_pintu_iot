<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Exception;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileComponent extends Component
{
    use WithFileUploads;

    public $email, $name, $gender, $role, $phone;
    public $password, $password_confirmation;
    public $avatar, $avatar_file, $last_avatar_temp;
    public $iteration = 0;

    public function render(Request $request)
    {
        // get user data
        $user = User::find($request->user()->id);

        // configure avatar
        if ($user->avatar == null) {
            $this->avatar_file = '02943e5368adf6cc72f4a2e0a435090b.png';
        } else {
            $this->avatar_file = $user->avatar;
        }

        return view('livewire.profile-component', ['user' => $user]);
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
        // validate input
        $this->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        // get last avatar
        $user = User::where('id', $request->user()->id)->first();

        // delete last avatar
        if ($user->avatar != null) {
            Storage::disk('local')->delete('/images/' . $user->avatar);
        }

        // set file name
        $name = md5(Carbon::now()) . '.' . $this->avatar->getClientOriginalExtension();

        try {

            // store avatar
            $this->avatar->storeAs('images', $name);
            $user->avatar = $name;
            $user->save();
            $this->closeModal('editAvatar');
            $this->dispatchBrowserEvent('avatar_change', $name);
            Log::info('avatar change', ['user' => $user]);
        } catch (Exception $e) {
            Log::error('avatar change failed', ['user' => $user, 'error' => $e]);
        }
    }

    public function editProfile(Request $request)
    {
        $user = $request->user();

        $this->resetInput();

        $this->name   = $user->name;
        $this->email  = $user->email;
        $this->gender = $user->gender;
        $this->phone  = $user->phone;

        $this->dispatchBrowserEvent('modal_open', 'editProfile');
    }

    public function storeProfile(Request $request)
    {
        $user = $request->user();
        $id   = $user->id;

        $this->validate([
            'name'   => ['required', 'min:4', Rule::unique('users')->ignore($id)],
            'email'  => ['required', 'email', Rule::unique('users')->ignore($id)],
            'gender' => ['required'],
            'phone'  => ['required', 'numeric', Rule::unique('users')->ignore($id), 'digits_between:11,13'],
        ]);

        // get user
        $user = User::where('id', $id)->first();

        // set user profile
        $user->email  = $this->email;
        $user->name   = $this->name;
        $user->gender = $this->gender;
        $user->phone  = $this->phone;

        try {
            // update profile
            $user->save();

            // notification
            session()->flash('update_success', 'Profil Anda');
            Log::info('update profile', ['user' => $user]);
        } catch (Exception $e) {

            // notification
            session()->flash('update_failed', 'Profil Anda');
            Log::error('update profile failed', ['user' => $user, 'error' => $e]);
        }

        $this->dispatchBrowserEvent('name_change', $user->name);
        $this->closeModal('editProfile');
    }

    public function confirm(Request $request)
    {
        // validate input
        $this->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        // get user
        $user = User::find($request->user()->id);

        // check input password
        if (Hash::check($this->password, $user->password)) {

            // open new password
            $this->closeModal('confirmPassword');
            $this->openModal('changePassword');
        } else {

            // input password not match
            $this->closeModal('confirmPassword');
            session()->flash('password_failed', 'Password');
        }
    }

    public function storePassword(Request $request)
    {
        // validate input
        $this->validate([
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        // get user
        $user = User::find($request->user()->id);

        try {
            // update password
            $user->forceFill(['password' => Hash::make($this->password)]);
            $user->save();

            // notification
            session()->flash('update_success', 'Password');
            $this->closeModal('changePassword');
            Log::info('change password', ['user' => $user]);
        } catch (Exception $e) {

            // notification
            session()->flash('update_failed', 'Password');
            Log::error('change password failed', ['user' => $user, 'error' => $e]);
        }
    }
}
