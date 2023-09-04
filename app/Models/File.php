<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['name','path','size','type'];

    public function user()
    {
        return $this->belongsTo(User::class,'avatar','uuid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'metadata.image','uuid');
    }

}
