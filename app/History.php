<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
	protected $fillable = ['link_id', 'hash'];
    public function link()
    {
    	return $this->belongsTo(Link::class);
    }
}
