<?php

namespace App\Http\Controllers\BackPanel;

use App\Http\Controllers\Controller;
use App\Models\BackPanel\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function list()
    {
        try {
            $categories = Category::where('status', 'Y')->get();

            $type = 'success';
            $message = 'Successfully category fetched';
            $response = [
                'categories' => $categories,
            ];
            // dd($response);
        } catch (Exception $e) {
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        }
        return response()->json(['type' => $type, 'message' => $message, 'response' => $response]);
    }

    public function save(Request $request)
    {
        try {
            $rules = [
                'name' => 'required | string | min:3 | max:100',
                'order' => 'required | integer | min:1 | max:255',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 1);
            }

            $post = $request->all();
            $type = 'success';
            $message = 'Category created successfully';
            $response = [];

            DB::beginTransaction();
            $result = Category::saveData($post);
            if (!$result) {
                throw new Exception('Could not save category', 1);
            }
            DB::commit();
            $response['redirect_url'] = '/api/addcategory';
        } catch (ValidationException $e) {
            DB::rollBack();
            $type = 'error';
            $message = $e->getMessage();
            $response = false;
        } catch (Exception $e) {
            DB::rollBack();
            $type = 'error';
            $message = 'Something Went Wrong';
            $response = false;
        }
        return response()->json(['type' => $type, 'message' => $message, 'response' => $response]);
    }
}
