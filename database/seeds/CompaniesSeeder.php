<?php

use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Company::create([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'website' => 'www.'.str_random(10).'.com',
            'logo' => '',
            'password' => bcrypt('secret'),
        ]);
    }
}
