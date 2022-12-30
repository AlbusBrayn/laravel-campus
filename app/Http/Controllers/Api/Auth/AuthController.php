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
            'password' => 'required|min:8',
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

       /*
            $exp = explode('@', $request->email);
            if ($school->email_pattern !== $exp[1]) {
                return response(['status' => 'error', 'message' => 'validate error!', 'data' => [
                    'email' => [
                        'The email does not match school mail.'
                    ]
                ]], 400);
            }
        */

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

    public function logout(Request $request)
    {
        $user = \Auth::user()->token();
        $user->revoke();

        return response(['status' => 'success', 'message' => 'Başarıyla çıkış yaptın.']);
    }

    public function otp(Request $request)
    {
        $user = $request->user();
        $code = rand(1000, 9999);

        if ($user->otp_reset_time) {
            if (time() < $user->otp_reset_time) {
                $c = $user->otp_reset_time - time();
                return response(['status' => 'error', 'message' => 'Tekrar doğrulama maili göndermek için '. $c .' saniye beklemen gerek.'], 400);
            } else {
                $user->otp_code = $code;
                $user->otp_reset_time = strtotime('+3 minutes');
                $this->sendMail('email.otp', ['token' => $code, 'message' => 'Sistem kaydını tamamlamak için kodu uygulamaya girin.'], $user->email, 'Campus A+ Doğrulama Kodu');
            }
        } else {
            $user->otp_code = $code;
            $user->otp_reset_time = strtotime('+3 minutes');
            $this->sendMail('email.otp', ['token' => $code, 'message' => 'Sistem kaydını tamamlamak için kodu uygulamaya girin.'], $user->email, 'Campus A+ Doğrulama Kodu');
        }

        $user->save();
        return response(['status' => 'success', 'Doğrulama kodu email adresinize gönderildi.']);
    }

    public function otpCheck(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => 'required|int'
        ]);
        $validator->setAttributeNames([
            'code' => 'Doğrulama kodu'
        ]);

        $user = $request->user();

        if ($user->otp_code) {
            if (time() > $user->otp_reset_time) {
                return response(['status' => 'error', 'message' => 'Doğrulama kodunun geçerlilik süresi doldu. Yeniden göndermeyi deneyin.'], 400);
            }

            if ($request->code === $user->otp_code) {
                $user->status = 2;
                $user->save();

                return response(['status' => 'success', 'message' => 'Email adresi başarıyla doğrulandı.']);
            } else {
                return response(['status' => 'error', 'message' => 'Doğrulama kodu yanlış, lütfen tekrar deneyin.'], 400);
            }
        } else {
            return response(['status' => 'error', 'message' => 'Hesaba tanımlı bir doğrulama kodu bulunamadı.'], 400);
        }
    }
}
