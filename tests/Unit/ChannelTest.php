<?php

namespace Tests\Unit;

use App\Models\Thread;
use Tests\TestCase;
use App\Models\Channel;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_channel_consists_of_threads()
    {
		$channel = create(Channel::class);
		$thread = create(Thread::class, ['channel_id'=>$channel->id]);

		$this->assertTrue($channel->threads->contains($thread));
    }
}
