<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @var array<string>
     */
    protected $fillable = ['type', 'details'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Order,Payment>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
