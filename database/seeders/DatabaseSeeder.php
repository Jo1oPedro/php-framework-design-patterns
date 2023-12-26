<?php

namespace Database\Seeders;

use Cascata\Framework\Database\Seeder;
use Database\Factories\PostFactory;

class DatabaseSeeder implements Seeder
{
    public function run(): void
    {
        PostFactory::count(3)->create();
    }
}