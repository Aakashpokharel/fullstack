<?php

namespace App\Http\Controllers\BackPanel;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $type = 'success';
            $message = 'This is a home page';
            $response = [];

            // dd(Auth::user());

            $data = [
                'type' => $type,
                'message' => $message,
                'response' => $response,
            ];
            return response()->json($data);
        } catch (Exception $e) {
            $data['type'] = 'error';
            $data['message'] = $e->getMessage();
            $data['response'] = false;
        }
    }
}
