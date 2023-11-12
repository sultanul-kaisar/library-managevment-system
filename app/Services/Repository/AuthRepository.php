<?php

namespace App\Services\Repository;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Interface\AuthInterface;
use App\Traits\Base;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthRepository implements AuthInterface
{
   use Base;

   public function login($request, $role)
    {
      try {
         $user = User::where('email', strtolower($request->email))->first();

         Log::info("User info::" . json_encode($user));
         Log::info("Request::" . json_encode($request));

         if (!$user) return Base::fail("User not found!");

         $credentials = $request->only(['email', 'password']);

         if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) return Base::fail('Invalid Credentials');

         if (Auth::user()->role != $role) return Base::fail("You can not login this app with this account");

         $accessToken = Auth::user()->createToken('authToken')->accessToken;

         $data = [
            'token' => $accessToken,
            'user' => new UserResource(Auth::user()),
         ];
         // ! Checking if activated
         
         if (Auth::user()->is_active) {
            return Base::pass('Login Successful', $data);
         } else {
            return Base::fail('Account is deactivate, please contact with admin');
         }
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
    }

   public function getUserInfo()
   {
      try {
         $user = Auth::user();
         if (!$user) return Base::fail('User not found!');

         $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image,
            'role' => $user->role,
            'is_active' => $user->is_active,
         ];
         return Base::success('User Information', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function registerUser($request, $role)
   {
      try {

         $res = $this->createUser($request);
         if (!$res->success) return Base::fail($res->message);

         $user = $res->data;

         $accessToken = $user->createToken('authToken')->accessToken;
         $data = [
             'token' => $accessToken,
             'user' => new UserResource($user),
         ];

         return Base::pass('Registration Successful', $data);
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function logout($request)
   {
      try {
         if (!Auth::user()) return Base::fail('User not logged in');
         $user = Auth::user()->token();
         $user->revoke();
         return Base::pass('Logout successfully');
      } catch (Exception $e) {
         return Base::exception_fail($e);
      }
   }

   public function createUser($data)
    {
        try {
            // Create User
            $user = new User();
            $user->name = $data['name'];
            $user->email = strtolower($data['email']);
            $user->password = Hash::make($data['password']);
            $user->role = 'user';
            $user->save();

            return Base::pass(null, $user);
        } catch (Exception $e) {
            return Base::exception_fail($e);
        }
    }
}