<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'password', 'visibility'];

    public function history()
    {
    	return $this->hasMany(History::class);
    }
}
