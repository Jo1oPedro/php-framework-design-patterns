<?php

namespace Database\Factories;

use Cascata\Framework\Database\Faker;

class PostFactory extends Faker
{
    public function definition()
    {
        return [
            'title' => $this->factory->name('male'),
            'body' => $this->factory->text(20),
        ];
    }
}