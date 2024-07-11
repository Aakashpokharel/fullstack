<?php

namespace App\Models\BackPanel;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    public static function saveData($post){
        try {
            $dataArray = [
                'name' => $post['name'],
                'slug' => Str::slug($post['name']) . '-' . time(),
                'order' => $post['order'],
            ];

            if(!empty($post['id'])){
                $dataArray['updated_at'] = Carbon::now();
                if(!Category::where('id',$post['id'])->update($dataArray)){
                    throw new Exception('Could not update category', 1);
                }
            }else{
                $dataArray['created_at'] = Carbon::now();
                if(!Category::insert($dataArray)){
                    throw new Exception('Could not add category',1);
                }
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function deleteData($post){
        try{
            $updateArray = [
                'status'=> 'N',
                'updated_at' => Carbon::now(),
            ];

            if(!empty($post['id'])){
                if(!Category::where('id',$post['id'])->update($updateArray)){
                    throw new Exception('Could not update category', 1);
                }
            }
            return true;
        }catch(Exception $e){
            throw $e;
        }
    }
}
