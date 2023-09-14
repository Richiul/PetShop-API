<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'slug', 'content', 'metadata'];
    /**
     * Define a one-to-one relationship with the File model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<File>
     */
    public function image()
    {
        return $this->hasOne(File::class, 'metadata.image', 'uuid');
    }
}
