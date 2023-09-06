<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JwtToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','unique_id','token_title','expires_at','last_used_at','refreshed_at'];

    protected $hidden = ['unique_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
