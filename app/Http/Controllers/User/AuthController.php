<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Services\Interface\AuthInterface;
use App\Traits\Base;

class AuthController extends Controller
{
    use Base;

    private $authRepository;

    public function __construct(AuthInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authRepository->login($request, request()->header('app_role'));
        return $data->success ? Base::success($data->message, $data->data, 'success', $data->type) : Base::error($data->message, $data->data, 'error', $data->type);
    }

    public function registerUser(RegistrationRequest $request)
    {
        $data = $this->authRepository->registerUser($request, request()->header('app_role'));
        return $data->success ? Base::success($data->message, $data->data, 'success', $data->type) : Base::error($data->message, $data->data, 'error', $data->type);
    }

    public function getUserInfo()
    {
        return $this->authRepository->getUserInfo();
    }

    public function logout(Request $request)
    {
        $data = $this->authRepository->logout($request, request()->header('app_role'));
        return $data->success ? Base::success($data->message, $data->data, 'success', $data->type) : Base::error($data->message, $data->data, 'error', $data->type);
    }
}
