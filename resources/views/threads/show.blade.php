@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                                <h4>{{ $thread->title }}</h4>
                            </span>
                            @can('update', $thread)
                                <form action="{{ $thread->path() }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>

                    </div>

                    <div class="panel-body">
                        <article>
                            <div class="body">
                                {{ $thread->body }}
                            </div>
                        </article>
                    </div>
                </div>
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach
                {{ $replies->links() }}
                @if(auth()->check())
                    <form action="{{ $thread->path() . "/replies" }}" method="post">
                        <div class="form-group">
                            <label for="body">Reply:</label>
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default pull-right">Post</button>
                        </div>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <article>
                            <div class="body">
                                <p>This thread was published {{ $thread->created_at->diffForHumans() }}
                                    by <a href="#">{{ $thread->creator->name }}</a>
                                    and currently has {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
