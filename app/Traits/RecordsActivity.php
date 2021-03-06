<?php
/**
 * Created by PhpStorm.
 * User: peterojo
 * Date: 7/2/17
 * Time: 12:06 AM
 */

namespace App\Traits;

use App\Models\Activity;

trait RecordsActivity {

	protected static function bootRecordsActivity() {
		if (auth()->guest()) return;
		foreach (static::getActivitiesToRecord() as $event) {
			static::$event(function($model) use ($event) {
				$model->recordActivity($event);
			});
		}

		static::deleting(function($model) {
			$model->activity()->delete();
		});
	}

	protected static function getActivitiesToRecord() {
		return ['created'];
	}

	public function activity() {
		return $this->morphMany(Activity::class, 'subject');
	}

	public function recordActivity( $event ) {
		$this->activity()->create([
			'type'         => $this->getActivityType( $event ),
			'user_id'      => auth()->id(),
		]);
	}

	public function getActivityType( $event ) {
		$type = strtolower( ( new \ReflectionClass( $this ) )->getShortName() );
		return $event . '_' . $type;
	}
}