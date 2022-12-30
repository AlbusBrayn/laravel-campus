<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        $validator->setAttributeNames([
            'email' => 'Email',
            'password' => 'Şifre'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $school = School::first();
        if (!$school) {
            return response(['status' => 'error', 'message' => 'Sisteme kayıtlı bir okul bulunamadı!'], 400);
        }

        $exp = explode('@', $request->email);
        if ($school->email_pattern !== $exp[1]) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => [
                'email' => [
                    'The email does not match school mail.'
                ]
            ]], 400);
        }

        $data = $validator->validated();
        $data['password'] = bcrypt($request->password);
        $data['school_id'] = $school->id; //@todo: multiple school support
        $user = User::create($data);
        $user['school'] = $user->school;

        $token = $user->createToken('Personel Access Token')->accessToken;

        return response(['user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $validator->setAttributeNames([
            'email' => 'Email',
            'password' => 'Şifre'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $data = $validator->validated();
        if (!auth()->attempt($data)) {
            return response(['status' => 'error', 'message' => 'Email veya şifre hatalı. Lütfen tekrar deneyin.'], 400);
        }

        $user = auth()->user();

        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
        $token = $user->createToken('API Token')->accessToken;
        unset($user['tokens']);
        $user['school'] = $user->school;

        return response(['user' => $user, 'token' => $token]);
    }
}
