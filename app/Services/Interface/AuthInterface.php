<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function login($request, $role);
    public function registerUser($request, $role);
    public function getUserInfo();
    public function logout($request);
}