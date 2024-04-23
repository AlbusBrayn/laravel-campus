<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\School;
use App\Models\User;
use App\Models\UserMajor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'device_id' => 'nullable|string'
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

        /**
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
        $user['device_id'] = $request->device_id;

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
        $user = $request->user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

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
                $this->sendMail('email.otp', ['token' => $code], $user->email, 'Campus A+ Doğrulama Kodu');
            }
        } else {
            $user->otp_code = $code;
            $user->otp_reset_time = strtotime('+3 minutes');
            $this->sendMail('email.otp', ['token' => $code], $user->email, 'Campus A+ Doğrulama Kodu');
        }

        $user->save();
        return response(['status' => 'success', 'message' => 'Doğrulama kodu email adresinize gönderildi.']);
    }

    public function otpCheck(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => 'required|int'
        ]);
        $validator->setAttributeNames([
            'code' => 'Doğrulama kodu'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();

        if ($user->otp_code) {
            if (time() > $user->otp_reset_time) {
                return response(['status' => 'error', 'message' => 'Doğrulama kodunun geçerlilik süresi doldu. Yeniden göndermeyi deneyin.'], 400);
            }

            if ($request->code == $user->otp_code) {
                $user->status = 2;
                $user->email_verified_at = Carbon::now();
                $user->otp_code = null;
                $user->otp_reset_time = null;
                $user->save();

                return response(['status' => 'success', 'message' => 'Email adresi başarıyla doğrulandı.']);
            } else {
                return response(['status' => 'error', 'message' => 'Doğrulama kodu yanlış, lütfen tekrar deneyin.'], 400);
            }
        } else {
            return response(['status' => 'error', 'message' => 'Hesaba tanımlı bir doğrulama kodu bulunamadı.'], 400);
        }
    }

    public function info(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'major_id' => 'required|int'
        ]);

        $validator->setAttributeNames([
            'name' => 'İsim',
            'major_id' => 'Bölüm'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = $request->user();
        $major = Major::findOrFail($request->major_id);

        $user->name = $request->name;

        UserMajor::updateOrCreate(
            [
                'user_id' => $user->id,
                'school_id' => $user->school_id
            ],
            [
                'major_id' => $major->id
            ]
        );

        $user->status = 4;
        $user->save();

        return response(['status' => 'success', 'message' => 'Kullanıcı bilgileri başarıyla kaydedildi.']);
    }

    public function forget(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        $validator->setAttributeNames([
            'email' => 'Email'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = User::where(['email' => $request->email])->first();
        if ($user) {
            $code = rand(1000, 9999);
            if ($user->forget_expire) {
                if (time() > $user->forget_expire) {
                    $user->forget_code = $code;
                    $user->forget_expire = strtotime('+3 minutes');
                    $this->sendMail('email.forget', ['token' => $code], $user->email, 'Campus A+ Şifre Sıfırlama');
                }
            } else {
                $user->forget_code = $code;
                $user->forget_expire = strtotime('+3 minutes');
                $this->sendMail('email.forget', ['token' => $code], $user->email, 'Campus A+ Şifre Sıfırlama');
            }

            $user->save();
        }

        return response(['status' => 'success', 'message' => 'Girdiğiniz mail adresi ile eşleşen bir hesap varsa doğrulama kodu gönderilecek.']);
    }

    public function forgetCheck(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|int',
            'password' => 'required|string|min:8'
        ]);

        $validator->setAttributeNames([
            'email' => 'Email',
            'code' => 'Doğrulama kodu',
            'password' => 'Şifre'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        $user = User::where(['email' => $request->email])->first();
        if ($user) {
            if ($user->forget_code == $request->code) {
                $user->password = bcrypt($request->password);
                $user->forget_code = null;
                $user->forget_expire = null;

                $user->tokens->each(function ($token, $key) {
                    $token->delete();
                });
                $user->save();
                return response(['status' => 'success', 'message' => 'Şifre başarıyla değiştirildi!']);
            }
        }

        return response(['status' => 'error', 'message' => 'Şifre değiştirme işlemi başarısız!'], 400);
    }

    public function forgetControl(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|int'
        ]);

        $validator->setAttributeNames([
            'email' => 'Email',
            'code' => 'Doğrulama kodu'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => 'validate error!', 'data' => $validator->errors()], 400);
        }

        if (User::where(['email' => $request->email, 'forget_code' => $request->code])->exists()) {
            return response(['status' => 'success', 'data' => true]);
        } else {
            return response(['status' => 'error', 'data' => false], 400);
        }
    }
}
