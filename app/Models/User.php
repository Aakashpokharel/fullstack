<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function ApiTokenUpdate($post)
    {
        try {
            $updateArray = [
                'api_token' => $post
            ];

            if (!User::where('id', Auth::user()->id)->update($updateArray)) {
                throw new Exception("Could't update api token.", 1);
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function resetPassword($post)
    {
        // dd($post);
        try {
            $insertArray = [
                'password' => bcrypt($post['new_password']),
            ];
            $id =Auth::guard('api')->user()->id;
            
            if (!empty($id)) {
                $insertArray['updated_at'] = Carbon::now();
                $insertArray['first_time_login'] = Carbon::now();
                $insertArray['email_verified_at'] = Carbon::now();
                if (!User::where('id', $id)->update($insertArray)) {
                    throw new Exception("Couldn't reset password");
                }
                return true;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
