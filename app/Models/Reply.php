<?php

namespace App\Models;

use App\Traits\Favouritable;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use SoftDeletes, Favouritable, RecordsActivity;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	protected $fillable = ['body', 'user_id'];

	protected $with = ['owner', 'favourites'];

	public function owner () {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function thread () {
		return $this->belongsTo(Thread::class);
	}
	
	public function path () {
		return $this->thread->path()."#reply-".$this->id;
	}
}
