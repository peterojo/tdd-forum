<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    function getRouteKeyName() {
	    return "slug";
    }

	public function threads() {
		return $this->hasMany(Thread::class);
    }
}
