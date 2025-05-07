<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function loginview(){  
        return view('login', ['title' => 'Login Page']);
    }

    public function register(){
        return view('sign_up', ['title' => 'Register']);
    }

    public function dashboard()
    {
        return view('home', ['tite' => 'Home Page']);
    }

    public function profile()
    {
        return view('profile', ['title' => 'Profile Page']);
    }

    public function details($id){
        return view('details', ['title' => 'Ticket Deatils']);
    }
}
