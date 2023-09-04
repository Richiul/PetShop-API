<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title','price','description','metadata'];

    public function category(){
        return $this->hasOne(Category::class,'uuid','category_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class,'uuid','metadata.brand');
    }

    public function image()
    {
        return $this->hasOne(File::class,'uuid','metadata.image');
    }

    public function orders()
    {
        return $this->belongsTo(Order::class,'products.product','uuid');
    }
}
