<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class Books extends Model
{
    use HasFactory;
    use Searchable;
    protected $with = [
        'author:id,name',
        'publisher:id,name',
        'genre:id,name'
    ];
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'isbn' => $this->isbn,
            'published_on' => $this->published_on,
            'author' => $this->author->name,
            'publisher' => $this->publisher->name,
            'genre' => $this->genre->name,
        ];
    }


    public function genre(): HasOne
    {
        return $this->hasOne(Genre::class,'id','genre_id');
    }
    public function author(): HasOne
    {
        return $this->hasOne(Author::class,'id','author_id');
    }
    public function publisher() : HasOne
    {
        return $this->hasOne(Publisher::class,'id','publisher_id');
    }


}
