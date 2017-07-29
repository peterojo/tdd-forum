<?php

namespace App\Http\Controllers;

use App\Filters\ThreadsFilters;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
	/**
	 * ThreadController constructor.
	 */
	public function __construct()
	{
		$this->middleware(['auth'])->except(['index', 'show']);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @param Channel $channel
	 *
	 * @param ThreadsFilters $filters
	 *
	 * @return \Illuminate\Http\Response
	 * @internal param null $channelSlug
	 */
    public function index(Channel $channel, ThreadsFilters $filters)
    {
	    $threads = $this->getThreads( $channel, $filters );

	    if (request()->wantsJson()) {
	    	return $threads;
	    }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$this->validate($request, [
    		'channel_id' => 'required|exists:channels,id',
    		'title' => 'required',
		    'body' => 'required'
	    ]);

        $thread = Thread::create([
        	'user_id' => auth()->id(),
	        'channel_id' => request('channel_id'),
        	'title' => request('title'),
	        'body' => request('body')
        ]);

        return redirect($thread->path())->with('flash', 'Your thread has been created.');
    }

	/**
	 * Display the specified resource.
	 *
	 * @param $channelId
	 * @param  Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function show($channel, Thread $thread)
    {
    	//return $thread->replies;
    	$replies = $thread->replies()->paginate(20);
    	return view('threads.show', compact('thread', 'replies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $channel
	 * @param  Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function destroy($channel, Thread $thread)
    {
    	$this->authorize('update', $thread);

        $thread->forceDelete();

        if (request()->wantsJson())
            return response([], 204);

        return redirect('/threads');
    }

	/**
	 * @param Channel $channel
	 * @param ThreadsFilters $filters
	 *
	 * @return mixed
	 */
	protected function getThreads( Channel $channel, ThreadsFilters $filters ) {
		$threads = Thread::filter( $filters )->latest();

		if ( $channel->exists ) {
			$threads->where( 'channel_id', $channel->id );
		}

		//dd($threads->toSql());

		$threads = $threads->get();

		return $threads;
	}
}
