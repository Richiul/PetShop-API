<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['uuid','title','slug'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Product,Brand>
     */
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class,'metadata.brand','uuid');
    }

}
