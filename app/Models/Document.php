<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title',
        'content',
        'source'
    ];

    public function chunks()
    {
        return $this->hasMany(DocumentChunk::class);
    }
}
