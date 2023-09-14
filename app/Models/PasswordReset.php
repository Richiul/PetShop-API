<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['email','token'];

    public $timestamps = false;
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,PasswordReset>
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'email','email');
    }

}
