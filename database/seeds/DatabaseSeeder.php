<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
		$faker = Faker::create();


		$now = Carbon::now();

		$tags = [];

		for($i = 0; $i < 10; $i++){
			$tags[] = DB::table('tags')->insertGetId([
				'name'          => $faker->word,
				'created_at' => $now,
				'updated_at' => $now
			]);
		}

		$tags = collect($tags);

		for($i = 1; $i < 51; $i++){

			$id = DB::table('books')->insertGetId([
				'name'          => $faker->sentence(2),
				'image'          => $i . '.jpg',
				'authors'   => $faker->name,
				'year'      => $faker->year,
				'created_at' => $now,
				'updated_at' => $now
			]);

			$random = $tags->random(rand(0, 3));

			foreach($random as $tag_id){
				DB::table('book_tags')->insert([
					'book_id' => $id,
					'tag_id' => $tag_id
				]);
			}
		}

		User::create([
			'name' => 'test',
			'email' => 'test@test.com',
			'password' => app('hash')->make('test1234'),
			'api_token' => 'test-token'
		]);


    }
}
