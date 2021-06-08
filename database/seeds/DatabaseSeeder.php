<?php

use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateAll();

        $faker = Faker::create();

        User::create([
            'name' => 'Pablo',
            'last_name' => 'Casal',
            'birthday' => \Carbon\Carbon::parse('1995-06-10'),
            'email' => 'pablo@test.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'verification_token' => \Illuminate\Support\Str::uuid()
        ]);

        $games = [
            Product::create([
                'name' => 'Uncharted 4',
                'cover' => 'https://zonageek.es/wp-content/uploads/2019/06/uncharted.jpg',
                'description' => 'Uncharted 4',
                'price' => $faker->randomFloat(2, 10, 90),
                'featured' => $faker->boolean(30),
                'online' => $faker->boolean()
            ]),
            Product::create([
                'name' => 'Flight Simulator 2020',
                'cover' => 'https://culturageek.com.ar/wp-content/uploads/2020/08/Culturageek.com_.ar-Microsoft-Flight-Simulator-1.jpg',
                'description' => 'Flight Simulator 2020',
                'price' => $faker->randomFloat(2, 10, 90),
                'featured' => $faker->boolean(30),
                'online' => $faker->boolean()
            ]),
            Product::create([
                'name' => 'Animal Crossing',
                'cover' => 'https://external-preview.redd.it/XmYU1C6KD9q1m9qY1hnedMhwYbwdWf3J2F-BcDpiav0.png?width=640&crop=smart&format=pjpg&auto=webp&s=2360ca1c22804ef5bb63b6405bed169d4c04c620',
                'description' => 'Animal Crossing New Horizons',
                'price' => $faker->randomFloat(2, 10, 90),
                'featured' => $faker->boolean(30),
                'online' => $faker->boolean()
            ]),
            Product::create([
                'name' => 'Assasins Creed - Valhalla',
                'cover' => 'https://cdnb.artstation.com/p/assets/images/images/027/128/699/large/seed-seven-se7ed-assassin-valhalla-cpt2-b.jpg?1590674001',
                'description' => 'Assasins Creed - Valhalla',
                'price' => $faker->randomFloat(2, 10, 90),
                'featured' => $faker->boolean(30),
                'online' => $faker->boolean()
            ])
        ];

        /** @var Platform $xbox */
        $xbox = Platform::create([
            'name' => 'Xbox',
            'logo' => 'https://www.vhv.rs/dpng/d/208-2087625_download-icon-xbox-svg-eps-png-psd-ai.png'
        ]);

        /** @var Platform $pc */
        $pc = Platform::create([
            'name' => 'PC',
            'logo' => 'https://cdn1.iconfinder.com/data/icons/home-appliances-49/100/Home_Appliances_9-512.png'
        ]);

        /** @var Platform $ps4 */
        $ps4 = Platform::create([
            'name' => 'PS4',
            'logo' => 'https://image.flaticon.com/icons/png/512/588/588369.png'
        ]);

        /** @var Category $action */
        $action = Category::create([
            'name' => 'Accion',
            'active' => true
        ]);

        /** @var Category $flight */
        $flight = Category::create([
            'name' => 'Vuelo',
            'active' => true
        ]);

        /** @var Category $adventure */
        $adventure = Category::create([
            'name' => 'Aventura',
            'active' => true
        ]);

        foreach ($games as $game) {
            $xbox->products()->attach($game->id);
            $pc->products()->attach($game->id);
            $ps4->products()->attach($game->id);
        }

        $action->products()->attach($games[0]->id);
        $action->products()->attach($games[3]->id);
        $flight->products()->attach($games[1]->id);
        $adventure->products()->attach($games[2]->id);
    }

    private function truncateAll(): void
    {
        DB::statement("SET foreign_key_checks=0");
        $databaseName = DB::getDatabaseName();
        $tables = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");
        foreach ($tables as $table) {
            $name = $table->TABLE_NAME;
            //if you don't want to truncate migrations
            if ($name == 'migrations') {
                continue;
            }
            DB::table($name)->truncate();
        }
        DB::statement("SET foreign_key_checks=1");
    }
}
