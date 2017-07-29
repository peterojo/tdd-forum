<?php

namespace Tests\Feature;

use App\Models\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_favourite_anything()
    {
	    $this->withExceptionHandling()
		    ->post('/replies/1/favourites')
	        ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favourite_any_reply()
    {
    	$this->signIn();
    	$reply = create(Reply::class);
		// If I post to a "favourite" endpoint
		$this->post('/replies/' . $reply->id . '/favourites');
	    // It should be recorded in the db
	    //dd(\App\Models\Favourite::all());
	    $this->assertCount(1, $reply->favourites);
    }

    /** @test */
    public function an_auth_user_may_only_favourite_a_reply_once()
    {
        $this->signIn();
	    $reply = create(Reply::class);

	    try {
		    $this->post('/replies/' . $reply->id . '/favourites');
		    $this->post('/replies/' . $reply->id . '/favourites');
	    } catch (\Exception $e) {
	    	$this->fail("Did not expect to insert the same record twice");
	    }

	    $this->assertCount(1, $reply->favourites);
    }
}
