@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create New Thread</div>

                    <div class="panel-body">
                        <form action="/threads" method="post">
                        <div class="form-group{{ $errors->has('channel_id') ? " has-error" : "" }}">
                            <label for="channel_id">Choose a channel</label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value=""> -- Select One -- </option>
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id')==$channel->id?"selected":"" }}>{{ $channel->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('channel_id'))
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                    <strong>Error!</strong> {{ $errors->first('channel_id') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('title') ? " has-error" : "" }}">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
                            @if($errors->has('title'))
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                    <strong>Error!</strong> {{ $errors->first('title') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('body') ? " has-error" : "" }}">
                            <label for="body">Body</label>
                            <textarea name="body" id="body" rows="8" class="form-control" required>{{ old('body') }}</textarea>
                            @if($errors->has('body'))
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                    <strong>Error!</strong> {{ $errors->first('body') }}
                                </div>
                            @endif
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary">Publish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
