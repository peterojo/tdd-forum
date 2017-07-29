<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes, RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected static function boot() {
	    parent::boot();

	    static::addGlobalScope('replyCount', function ($builder) {
	    	$builder->withCount('replies');
	    });

	    static::deleting(function ($thread) {
	    	/*$thread->replies->each(function($reply) {
	    		$reply->forceDelete();
		    });*/
	    	$thread->replies->each->forceDelete();
	    });


//	    static::addGlobalScope('creator', function ($builder) {
//	    	$builder->with('creator');
//	    });
    }

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	public function path() {
		return "/threads/{$this->channel->slug}/{$this->id}";
	}

	public function replies() {
		return $this->hasMany(Reply::class);
	}

	public function creator() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function channel() {
		return $this->belongsTo(Channel::class);
	}

	public function addReply($reply) {
		$this->replies()->create($reply);
	}

	public function scopeFilter( $query, $filters ) {
		return $filters->apply($query);
	}

	public function getReplyCountAttribute() {
		return $this->replies()->count();
	}
}
