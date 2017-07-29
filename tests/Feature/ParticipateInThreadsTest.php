<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadsTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
		// given we have an authenticated user
	    $this->signIn(); // sets $user as the auth user

	    // and an existing thread
	    $thread = create(Thread::class);

	    // when the user adds a reply to the thread
	    $reply = make(Reply::class);
	    $this->post($thread->path() . '/replies', $reply->toArray());

	    // then their reply should be visble on the thread page
	    $this->get($thread->path())
	        ->assertSee($reply->body);
    }

    /** @test */
	public function unauthenticated_users_may_not_add_replies()
	{
		$this->withExceptionHandling()
			->post('threads/somechannel/1/replies', [])
			->assertRedirect('/login');
	}
	
	/** @test */
	public function a_reply_requires_a_body()
	{
	    $this->withExceptionHandling()->signIn();

		$thread = create(Thread::class);
		$reply = make(Reply::class, ['body' => null]);
		$this->post($thread->path() . '/replies', $reply->toArray())
			->assertSessionHasErrors('body');
	}
	
	/** @test */
	public function unauthorized_users_cannot_delete_replies()
	{
		$this->withExceptionHandling();
		$reply = create(Reply::class);
	      
		$this->delete('/replies/'.$reply->id)
	        ->assertRedirect('login');
		
		$this->signIn();
		$this->delete('/replies/'.$reply->id)
			->assertStatus(403);
	}
	
	/** @test */
	public function authorized_users_can_delete_replies()
	{
	    $this->signIn();
	    $reply = create(Reply::class, ['user_id' => auth()->id()]);
	    
	    $this->delete('/replies/'.$reply->id)->assertStatus(302);
	    
	    $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
	}
	
	/** @test */
	public function unauthorized_users_cannot_update_replies()
	{
		$this->withExceptionHandling();
		$reply = create(Reply::class);
		
		$this->patch('/replies/'.$reply->id)
		     ->assertRedirect('login');
		
		$this->signIn();
		$this->patch('/replies/'.$reply->id)
		     ->assertStatus(403);
	}
	
	/** @test */
	public function authorized_users_can_update_replies()
	{
		$this->signIn();
		$reply = create(Reply::class, ['user_id' => auth()->id()]);
		
		$updatedReply = 'You been changed, fool!';
		$this->patch( '/replies/' . $reply->id, [ 'body' => $updatedReply ]);
		
		$this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply ]);
	}
}
