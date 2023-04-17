<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard.profile');
    }

    public function getAvatar($file_name)
    {
        $file = storage_path('/app/images/' . $file_name);
        return response()->file($file);
    }
}