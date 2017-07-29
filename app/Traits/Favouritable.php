<?php
/**
 * Created by PhpStorm.
 * User: peterojo
 * Date: 6/5/17
 * Time: 12:10 AM
 */

namespace App\Traits;

use App\Models\Favourite;

trait Favouritable {

	public function favourites() {
		return $this->morphMany( Favourite::class, 'favourited' );
	}

	public function favourite() {
		$attributes = [ 'user_id' => auth()->id() ];
		if ( ! $this->favourites()->where( $attributes )->exists() ) {
			return $this->favourites()->create( $attributes );
		}
	}

	public function isFavourited() {
		return ! ! $this->favourites->where( 'user_id', auth()->id() )->count();
	}

	public function getFavouritesCountAttribute() {
		return $this->favourites->count();
	}
}