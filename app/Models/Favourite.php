<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
	use RecordsActivity;
    protected $guarded = [];
	
	public function favourited () {
		return $this->morphTo();
    }
}
