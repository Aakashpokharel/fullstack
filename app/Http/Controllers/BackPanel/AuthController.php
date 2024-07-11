<?php

namespace App\Http\Controllers\BackPanel;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\BackPanel\OtpLog;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // public function logout(Request $request)
    // {
    //     $user = Auth::guard('api')->user();

    //     if ($user) {
    //         // Invalidate the token
    //         $user->api_token = null;
    //         $user->save();

    //         return response()->json(['message' => 'Logged out successfully'], 200);
    //     }

    //     return response()->json(['message' => 'User not authenticated'], 401);
    // }
    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required | email',
                'password' => 'required'
            ];

            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            $type = 'success';
            $message = 'Logged in Successfully';
            $response = [];

            $post = $request->all();

            $credentials = [
                'email' => $post['email'],
                'password' => $post['password'],
                'status' => 'Y',
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if ($user->first_time_login == null) {
                    $otp = Str::random(6);
                    $otp = strtoupper($otp);
                    //save otp in database
                    $otpRecord = new OtpLog();
                    $otpRecord->otp_code = $otp;
                    $otpRecord->user_id = $user->id;
                    $otpRecord->created_at = Carbon::now();
                    $otpRecord->save();

                    Mail::to($user->email)->send(new OtpMail($otpRecord));

                    $token = Str::random(64);
                    $result = User::ApiTokenUpdate($token);
                    if (!$result) {
                        throw new Exception("Couldn't update API token.", 1);
                    }
                    $response['api_token'] = 'Bearer ' . $token;
                    $response['user_id'] = Auth::user()->id;
                    $response['first_time_login'] = Auth::user()->first_time_login;
                    $response['redirect_url'] = '/api/verifyotp';
                } else {
                    $response['user_id'] = Auth::user()->id;
                    $response['api_token'] = Auth::user()->api_token;
                    $response['redirect_url'] = '/api/dashboard';
                }
            } else {
                throw new Exception('Invalid Credentials or user is unactive.', 1);
            }
        } catch (Exception $e) {
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        } catch (ValidationException $e) {
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        }
        return response()->json(['type' => $type, 'message' => $message, 'response' => $response]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $rules = [
                'new_password' => 'required|string|min:8|max:100',
                'confirm_password' => 'required|string|min:8|max:100|same:new_password',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 1);
            }
            $post = $request->all();
            // dd($post);
            $type = 'success';
            $message = 'Password Updated Successfully.';
            $response = [];

            DB::beginTransaction();
            $result = User::resetPassword($post);
            if (!$result) {
                throw new Exception("Couldn't reset password.", 1);
            }
            DB::commit();
            $response['redirect_url'] = '/api/login';
        } catch (QueryException $e) {
            DB::rollback();
            // dd($e);
            $type = 'error';
            $message = 'Something Went Wrong';
            $response = false;
        } catch (Exception $e) {
            DB::rollback();
            // dd($e);
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        } catch (ValidationException $e) {
            // DB::rollback();
            // dd($e);
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        }
        // dd($type, $message);
        return response()->json(['type' => $type, 'message' => $message, 'response' => $response]);
    }
}
