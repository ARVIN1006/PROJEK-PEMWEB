<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function login()
    {
        return view('auth/login'); // arahkan ke folder views/auth/login.php
    }

    public function register()
    {
        return view('auth/register'); // ini memanggil file di app/Views/auth/register.php
    }
}
