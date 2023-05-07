<?php

namespace App\Http\Controllers\Web;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Door;

class DashboardController extends Controller
{
    protected $data = array();
    protected $data_user;

    private function setUserProfil(Request $request)
    {
        $user = $request->user();

        // set name and role
        $this->data['user_name']   = $user->name;
        $this->data['user_role']   = $user->role;
        $this->data['user_office'] = 'Tidak Ada';

        // get opereator office
        $office = Office::where('user_id', $user->id)->get('name');

        // check avatar
        if ($user->avatar == null) {
            $this->data['img_avatar'] = '02943e5368adf6cc72f4a2e0a435090b.png';
        } else {
            $this->data['img_avatar'] = $user->avatar;
        }

        // set operator office
        if (sizeof($office) != 0) {
            $this->data['user_office'] = $office[0]->name;
        }
    }

    public function get_avatar($file)
    {
        // return avatar
        return response()->file(storage_path('/app/images/' . $file));
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

    public function schedules(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.schedule', $this->data);
    }

    public function histories(Request $request)
    {
        // set user profil
        $this->setUserProfil($request);

        // render view
        return view('dashboard.histories', $this->data);
    }

    public function printPoster($id)
    {
        $door = Door::with('office')->where('id', $id)->first();

        if (!$door) {
            return 'tidak ditemukan';
        }

        return view('poster', ['door' => $door]);
    }
}
