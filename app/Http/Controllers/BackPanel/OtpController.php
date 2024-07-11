<?php

namespace App\Http\Controllers\BackPanel;

use App\Http\Controllers\Controller;
use App\Models\BackPanel\OtpLog;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    public function verifyOtp(Request $request)
    {
        try {
            $rules = [
                'otp_code' => 'required | string | min:6 | max:6',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }

            $type = 'success';
            $message = 'OTP verified successfully';
            $response = [];

            $post = $request->all();

            $result = OtpLog::where('user_id', Auth::guard('api')->user()->id)
                ->orderBy('id', 'desc')
                ->first();

            if (!$result) {
                throw new Exception("Otp not generated. Please try again later.", 1);
            }
            $generatedDateTime = new DateTime($result->created_at);
            $currentDateTime = new DateTime();

            // Calculate the time difference
            $timeDifference = $currentDateTime->getTimestamp() - $generatedDateTime->getTimestamp();

            // Check if the time difference is within 5 minutes (300 seconds)

            if ($timeDifference > 300) {
                throw new Exception("OTP Expired. Please try again with new request", 1);
            }

            if ($result->otp_code != $post['otp_code']) {
                throw new ValidationException("Wrong OTP. Please try again with correct otp.", 1);
            }
            $response['user_id'] = Auth::guard('api')->user()->id;
            $response['redirect_url'] = '/api/resetpassword';
        } catch (ValidationException $e) {
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        } catch (Exception $e) {
            $type = 'error';
            $message = $e->getMessage();
            $response['redirect_url'] = '/api/login';
        }
        return response()->json(['type' => $type, 'message' => $message, 'response' => $response]);
    }
}
