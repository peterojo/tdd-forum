<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Thread::class, function (Faker\Generator $faker) {
	return [
		'user_id' => function() {
			return factory(App\Models\User::class)->create()->id;
		},
		'channel_id' => function() {
			return factory(App\Models\Channel::class)->create()->id;
		},
		'title' => $faker->sentence,
		'body' => $faker->paragraph,
	];
});

$factory->define(App\Models\Channel::class, function (Faker\Generator $faker) {
	$name = $faker->word;
	return [
		'name' => $name,
		'slug' => $name,
	];
});

$factory->define(App\Models\Reply::class, function (Faker\Generator $faker) {
	return [
		'user_id' => function() {
			return factory(App\Models\User::class)->create()->id;
		},
		'thread_id' => function() {
			return factory(App\Models\Thread::class)->create()->id;
		},
		'body' => $faker->paragraph,
	];
});

// >>> $threads = factory('App\Models\Thread', 50)->create();
// >>> $threads->each(function ($thread) { factory('App\Models\Reply', 10)->create(['thread_id' => $thread->id]); });