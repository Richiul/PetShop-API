<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = ['content','metadata'];

    public function image()
    {
        return $this->hasOne(File::class,'metadata.image','uuid');
    }
}
