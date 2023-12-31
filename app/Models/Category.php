<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @var array<string>
     */
    protected $fillable = ['uuid', 'title', 'slug'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Product,Category>
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'category_id', 'uuid');
    }
}
