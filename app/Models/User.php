<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'address',
        'phone_number'
    ];

    public function file()
    {
        return $this->hasOne(File::class,'uuid','avatar');
    }

    public function order()
    {
        return $this->hasMany(Order::class,'user_id','id');
    }

    public function token()
    {
        return $this->hasOne(JwtToken::class,'user_id','id');
    }

    public function passwordReset()
    {
        return $this->hasMany(PasswordReset::class,'password_resets.email','email');
    }

}
