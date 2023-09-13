<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['uuid','title','slug'];

    public function product()
    {
        return $this->belongsTo(Product::class,'category_id','uuid');
    }
}
