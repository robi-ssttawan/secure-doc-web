<?php

namespace App\Controllers;

class Enkripsi extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('user/enkripsi_view');
    }
}