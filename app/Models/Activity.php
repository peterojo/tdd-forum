<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

	public function subject() {
		return $this->morphTo();
    }

	public static function feed( User $user, $take ) {
		return $user->activity()->latest()->with( 'subject' )->take( $take )->get()->groupBy( function ( $activity ) {
			return $activity->created_at->format( "Y-m-d" );
		} );
	}
}
