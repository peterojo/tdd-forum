<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Filters
{
	protected $request, $builder;
	protected $filters = [];
	/**
	 * ThreadsFilters constructor.
	 *
	 * @param Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * @param Builder $builder
	 *
	 * @return Builder|mixed
	 */
	public function apply( Builder $builder ) {
		$this->builder = $builder;

		foreach ( $this->getFilters() as $filter => $value) {
			if ( method_exists( $this, $filter ) )
			{
				$this->$filter($value);
			}
		}

		return $this->builder;
	}

	/**
	 * @return array
	 */
	protected function getFilters() {
		return $this->request->intersect( $this->filters );
	}
}