<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['products','address','delivery_fee','amount'];

    public function user()
    {
        return $this->belongsTo(User::class,'id','user_id');
    }

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class,'id','order_status_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class,'id','payment_id');
    }

    public function products()
{
    return $this->hasMany(Product::class, 'uuid','products.product');
}
}
