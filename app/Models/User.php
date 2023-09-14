<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    protected array $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'address',
        'phone_number',
        'uuid',
        'password',
        'is_admin',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $hidden = ['password', 'uuid', 'is_admin'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<File>
     */
    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'avatar');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Order>
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<JwtToken>
     */
    public function token(): HasOne
    {
        return $this->hasOne(JwtToken::class);
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<PasswordReset>
     */
    public function passwordReset(): HasOne
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }

    /**
     * Get the additional claims to be added to the JWT payload.
     *
     * @return array<string>
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Get the additional claims to be added to the JWT payload.
     *
     * @return array<string>
     */
    public function getJWTCustomClaims(): mixed
    {
        return ['uuid' => $this->uuid];
    }
}
