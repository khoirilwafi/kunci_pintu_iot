<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Avatar;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $data = array();
    protected $data_user;

    private function setUserProfil(Request $request)
    {
        $user = $request->user();

        // set name and role
        $this->data['user_name'] = $user->name;
        $this->data['user_role'] = $user->role;

        // get opereator office
        $office = Office::where('user_id', $user->id)->get('name');
        $img_avatar = Avatar::where('user_id', $user->id)->first();

        $this->data['user_office'] = 'Tidak Ada';

        if ($img_avatar == null) {
            $this->data['img_avatar'] = '02943e5368adf6cc72f4a2e0a435090b.png';
        } else {
            $this->data['img_avatar'] = $img_avatar->name;
        }

        // set operator office
        if (sizeof($office) != 0) {
            $this->data['user_office'] = $office[0]->name;
        }
    }

    public function index(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.home', $this->data);
    }

    public function operators(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.operator', $this->data);
    }

    public function offices(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.office', $this->data);
    }

    public function my_account(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.profile', $this->data);
    }

    public function users(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.user', $this->data);
    }

    public function doors(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.door', $this->data);
    }

    public function scedules(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.scedule', $this->data);
    }
}
