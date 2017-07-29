<reply :attributes="{{ $reply }}" inline-template v-cloak>
	<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex"><a href="{{ route('profile', $reply->owner) }}">
                {{ $reply->owner->name }}</a> said:
                <span class="text-muted">{{ $reply->created_at->diffForHumans() }}</span>
            </h5>

            <form action="/replies/{{ $reply->id }}/favourites" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default"{{ $reply->isFavourited() ? " disabled" : "" }}>
                    {{ $reply->favourites_count }} {{ str_plural('Favourite', $reply->favourites_count) }}
                </button>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <div class="body">
	        <div v-if="editing">
		        <div class="form-group">
			        <textarea class="form-control" v-model="body"></textarea>

			        <button class="btn btn-xs btn-primary" @click="update">Update</button>
			        <button class="btn btn-xs btn-link" @click.prevent="editing=false">Cancel</button>
		        </div>
	        </div>
            <div v-else v-text="body"></div>
        </div>
    </div>
	@can('update')
	<div class="panel-footer level">
		<button class="btn btn-default btn-xs mr-1" @click.prevent="editing=true">Edit</button>
		<button class="btn btn-danger btn-xs mr-1" @click.prevent="destroy">Delete</button>
	</div>
	@endcan
	</div>
</reply>