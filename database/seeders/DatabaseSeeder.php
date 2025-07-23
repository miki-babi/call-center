<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'phone' => '0967072576',
            'password' => bcrypt('12345678')
        ]);
        Shop::factory()->create([
            'name' => 'mexico',
            'consumer_key' => 'ck_a750906044c03362097f8f918d335429c1a506d8',
            'consumer_secret' => 'cs_cf4405d03ec421acb098bf22d10048d9a9c0cb54',
            'url' => 'https://beshgebeya.co/mexico',
        ]);
                // Shop::factory()->create([
                //     'name' => 'kadisco',
                //     'consumer_key' => 'ck_1234567890abcdef1234567890abcdef12345678',
                //     'consumer_secret' => 'cs_1234567890abcdef1234567890abcdef12345678',
                //     'url' => 'https://beshgebeya.co/kadisco',
                // ]);
        Shop::factory()->create([
            'name' => 'ayat',
            'consumer_key' => 'ck_7ec1f731a07c9ec77d087122f94354d43ef8f2af',
            'consumer_secret' => 'cs_2667463452d7a679653b00e1d7c17a8d1cc1ad39',
            'url' => 'https://beshgebeya.co/ayat',
        ]);
    }
}
