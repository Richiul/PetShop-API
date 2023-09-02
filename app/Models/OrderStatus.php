<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug'];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_status_id','id');
    }
}
