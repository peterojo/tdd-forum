<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function it_records_an_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities', [
        	'type' => 'created_thread',
	        'user_id' => auth()->id(),
	        'subject_id' => $thread->id,
	        'subject_type' => Thread::class
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }
    
    /** @test */
    public function it_records_an_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());
    }
    
    /** @test */
    public function it_fetches_an_activity_feed_for_any_user()
    {
    	$this->signIn();

	    create(Thread::class, ['user_id' => auth()->id()], 2);

	    auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);
	    // when we fetch their feed
	    $feed = Activity::feed( auth()->user(), 50 );
	    // it should return in the proper format
	    $this->assertTrue($feed->keys()->contains(Carbon::now()->format("Y-m-d")));
	    $this->assertTrue($feed->keys()->contains(Carbon::now()->subWeek(1)->format("Y-m-d")));
    }
}
