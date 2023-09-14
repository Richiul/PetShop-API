<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderStatus extends Model
{
    use HasFactory;
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'slug'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Order,OrderStatus>
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
