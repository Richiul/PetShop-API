<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements \Tymon\JWTAuth\Contracts\JWTSubject
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
        'avatar',
        'address',
        'phone_number',
        'uuid',
        'password'
    ];

    protected $hidden = ['password','uuid'];

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
        return $this->hasOne(PasswordReset::class,'email','email');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

     /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['uuid' => $this->uuid];
    }

}
