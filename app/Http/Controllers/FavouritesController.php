<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;

class FavouritesController extends Controller
{
	function __construct() {
		$this->middleware(['auth']);
	}

	public function store( Reply $reply ) {
		$reply->favourite();
		return back();
    }
}
