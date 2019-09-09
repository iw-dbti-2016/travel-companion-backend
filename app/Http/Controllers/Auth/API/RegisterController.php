<?php

namespace TravelCompanion\Http\Controllers\Auth\API;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use TravelCompanion\Http\Controllers\Controller;
use TravelCompanion\Traits\APIResponses;
use TravelCompanion\Traits\Auth\RegistersUsersWithToken;
use TravelCompanion\User;

class RegisterController extends Controller
{
	use RegistersUsersWithToken, APIResponses;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('auth:api')->except('register');
        $this->middleware('guest:api')->only('login');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            "first_name" => "required|min:2|max:50|regex:/^[A-Za-z-']+$/",
            "middle_name" => ["nullable", "max:100", "regex:/^((([A-Z]{1}\.)|([A-Za-z-']+)) ?)+$/"],
            "last_name" => "required|min:2|max:50|regex:/^[A-Za-z-']+$/",
            "username" => "required|min:4|max:40|regex:/^[A-Za-z0-9-.]+$/|unique:users",
            "email" => "required|max:80|email|unique:users",
            "password" => "required|min:6|confirmed",
        ]);
    }

    protected function validationFailed(\Illuminate\Validation\Validator $validator)
    {
        return $this->validationFailedResponse($validator);
    }

	protected function create(array $data)
	{
		return User::create([
            'first_name' => $data['first_name'],
            'middle_name' => isset($data['middle_name']) ? $data["middle_name"] : null,
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
	}

    protected function registered(Request $request, $user)
    {
        return $this->okResponse(null, [
            "token" => $this->token,
            "token_type" => 'bearer',
            "expires_in" => auth()->factory()->getTTL() * 60,
            "user" => $user,
        ], 201);
    }
}
