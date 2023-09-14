<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'price', 'description', 'metadata'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Category>
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'uuid', 'category_id');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Brand>
     */
    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class, 'uuid', 'metadata.brand');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<File>
     */
    public function image(): HasOne
    {
        return $this->hasOne(File::class, 'uuid', 'metadata.image');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Order,Product>
     */
    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'uuid', 'products.product');
    }
}
