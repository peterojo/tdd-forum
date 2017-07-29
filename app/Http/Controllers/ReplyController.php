<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	/**
	 * ReplyController constructor.
	 */
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	/**
	 * @param $channelId
	 * @param Thread $thread
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store($channelId, Thread $thread) {
		$this->validate(request(), ['body'=>'required']);

		$thread->addReply([
			'user_id' => auth()->id(),
			'body' => request('body')
		]);

		return redirect($thread->path())->with('flash', 'Your reply has been left.');
    }
	
	public function update ( Reply $reply ) {
		$this->authorize('update', $reply);
		
		$reply->update(['body' => request('body')]);
    }
	
	public function destroy ( Reply $reply ) {
		$this->authorize('update', $reply);
		
		$reply->forceDelete();
		
		if (!request()->ajax())
			return back();
    }
}
