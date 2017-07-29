@component('profiles.activitites.activity')
	@slot('heading')
		{{ $profileUser->name }} favourited a comment on
		<a href="{{ $activity->subject->favourited->path() }}">
			"{{ $activity->subject->favourited->thread->title }}"
		</a>
	@endslot

	@slot('body')
		{{ $activity->subject->favourited->body }}
	@endslot
@endcomponent
