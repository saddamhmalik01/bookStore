<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Books>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word,
            'author_id'=> Author::inRandomOrder()->first()->id,
            'genre_id'=> Genre::inRandomOrder()->first()->id,
            'description' => fake()->sentence,
            'isbn' => fake()->randomNumber(),
            'image' => 'https://png.pngtree.com/template/20210908/ourlarge/pngtree-creative-and-realistic-book-dummy-image_633548.jpg',
            'published_on' => fake()->date,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
        ];
    }
}
