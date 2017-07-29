<?php
/**
 * Created by PhpStorm.
 * User: peterojo
 * Date: 5/17/17
 * Time: 9:41 PM
 */

namespace App\Filters;


use App\Models\User;
use Illuminate\Http\Request;

class ThreadsFilters extends Filters
{
	protected $filters = ['by', 'popularity'];
	/**
	 * Filter the query by username
	 * @param $username
	 *
	 * @return mixed
	 * @internal param Builder $builder
	 */
	protected function by( $username ) {
		$user = User::where( 'name', $username )->firstOrFail();

		return $this->builder->where( 'user_id', $user->id );
	}

	protected function popularity() {
		return $this->builder->orderBy('replies_count', 'DESC');
	}
}