<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['products','address','delivery_fee','amount'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,Order>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<OrderStatus>
     */
    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class);
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Payment>
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Product>
     */
    public function products()
{
    return $this->hasMany(Product::class,'products.product','uuid');
}
}
