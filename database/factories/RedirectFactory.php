<?php

namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Redirect>
 */
class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
        ];
    }

    public function http()
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'http://' . $this->faker->domainName,
            ];
        });
    }

    public function https()
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'https://' . $this->faker->domainName,
            ];
        });
    }

    public function ftp()
    {
        return $this->state(function (array $attributes) {
            return [
                'url' => 'ftp://' . $this->faker->domainName,
            ];
        });
    }

    public function customUrl($customUrl)
    {
        return $this->state(function (array $attributes) use ($customUrl) {
            return [
                'url' => $customUrl,
            ];
        });
    }
}
