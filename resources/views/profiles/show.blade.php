@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>{{ $profileUser->name }}</h1>
                    </div>
                </div>
                @foreach($activities as $date => $activity)
                    <h4 class="page-header">{{ $date }}</h4>
		            @foreach($activity as $record)
			            @if(view()->exists('profiles.activitites.'.$record->type))
							@include('profiles.activitites.'.$record->type, ['activity' => $record])
			            @endif
		            @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection

