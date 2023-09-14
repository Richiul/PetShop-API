<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JwtToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'unique_id', 'token_title', 'expires_at', 'last_used_at', 'refreshed_at'];

    protected $hidden = ['unique_id'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,JwtToken>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
