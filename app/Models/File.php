<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['name','path','size','type'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,File>
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'avatar','uuid');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Product,File>
     */
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class,'metadata.image','uuid');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Post,File>
     */
    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class,'uuid','metadata.image');
    }
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Promotion,File>
     */
    public function promotion():BelongsTo
    {
        return $this->belongsTo(Promotion::class,'uuid','metadata.image');
    }

}
