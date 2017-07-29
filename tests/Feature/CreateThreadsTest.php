<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

    /**
     * @test
     */
    public function an_authenticated_user_can_create_new_threads()
    {
        // given we have a signed in user
	    $this->signIn();

	    // when we hit the endpoint to create a new thread
	    $thread = make(Thread::class);
	    $response = $this->post('/threads', $thread->toArray());

	    // and we visit the thread page
	    $this->get($response->headers->get('Location'))

	    // we should see the new thread
	        ->assertSee($thread->title)
		    ->assertSee($thread->body);
    }

    /** @test */
	public function guests_may_not_create_threads()
	{
		$this->withExceptionHandling();
		$this->get('/threads/create')
			->assertRedirect('/login');
		$this->post('/threads')
			->assertRedirect('/login');
	}
	
	/** @test */
	public function a_thread_requires_a_title()
	{
		$this->publishThread(['title'=>null])
			->assertSessionHasErrors('title');
	}

	/** @test */
	public function a_thread_requires_a_body()
	{
		$this->publishThread(['body'=>null])
			->assertSessionHasErrors('body');
	}

	/** @test */
	public function a_thread_requires_a_valid_channel()
	{
		factory(Channel::class, 2)->create();

		$this->publishThread(['channel_id'=>null])
			->assertSessionHasErrors('channel_id');

		$this->publishThread(['channel_id'=>999])
		     ->assertSessionHasErrors('channel_id');
	}
	
	/** @test */
	public function unauthorized_users_may_not_delete_threads()
	{
		$this->withExceptionHandling();
	    $thread = create(Thread::class);

		$response = $this->delete($thread->path());

		$response->assertRedirect('/login');

		$this->signIn();

		$this->delete($thread->path())->assertStatus(403);
	}
	
	/** @test */
	public function auth_users_can_delete_threads()
	{
	    $this->signIn();

	    $thread = create(Thread::class, ['user_id' => auth()->id()]);
	    $reply = create(Reply::class, ['thread_id' => $thread->id]);

	    $response = $this->json('DELETE', $thread->path());

	    $response->assertStatus(204);

	    $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
	    $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
	    $this->assertEquals(0, Activity::count());
	}

	public function publishThread($overrides)
	{
		$this->withExceptionHandling()->signIn();

	    $thread = make(Thread::class, $overrides);

	    return $this->post('/threads', $thread->toArray());
	}
}
