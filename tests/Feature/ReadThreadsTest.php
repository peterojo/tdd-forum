<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
	use DatabaseMigrations;

	public $thread;

	public function setUp() {
		parent::setUp();

		$this->thread = create(Thread::class);
	}

    /** @test */
    public function a_user_can_view_all_threads()
    {
    	$response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

	/** @test */
	public function a_user_can_view_a_single_thread() {
		$response = $this->get($this->thread->path());
		$response->assertSee($this->thread->title);
    }

    /** @test */
	public function a_user_can_read_replies_that_are_associated_with_a_thread() {
		$reply = create(Reply::class, ['thread_id' => $this->thread->id]);

		$this->get($this->thread->path())
			->assertSee($reply->body);
    }
    
    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
    	$channel = create(Channel::class);
    	$threadInChannel = create(Thread::class, ['channel_id'=>$channel->id]);
    	$threadNotInChannel = create(Thread::class);
        $this->get('/threads/' . $channel->slug)
	        ->assertSee($threadInChannel->title)
	        ->assertDontSee($threadNotInChannel->title);
    }
    
    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(User::class, ['name' => 'JohnDoe']));

        $threadByJohn = create(Thread::class, ['user_id' => auth()->id()]);
        $threadNotByJohn = create(Thread::class);

        $this->get('/threads?by=JohnDoe')
	        ->assertSee($threadByJohn->title)
	        ->assertDontSee($threadNotByJohn->title);
    }
    
    /** @test */
	public function a_user_can_filter_threads_by_popularity() {
		// Given we have 3 threads
		// with 2, 3 and 0 replies resp.
		$threadWithTwoReplies = create(Thread::class);
		create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

		$threadWithThreeReplies = create(Thread::class);
		create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

		$threadWithNoReplies = $this->thread;
		// when I filter all threads by popularity
		$response = $this->getJson('threads?popularity=1')->json();
		// then they should be returned from most replies to least
		//dd(array_column($response, 'replies_count'));
		$this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }
}
