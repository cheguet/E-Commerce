<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
         $title = fake()->unique()->name();
         $slug  = Str::slug($title);

         $subCategories = [7,8];
         $subCatRandKey = array_rand($subCategories);

         $brands = [5,6,7,8,9,10];
         $brandRandKey = array_rand($brands);
         
        return [

            'title' => $title,
            'slug'  => $slug,
            'category_id' => 99,
            'sub_category_id' => $subCategories[$subCatRandKey],
            'brand_id' =>  $brands = [$brandRandKe],
            'price' => rand(10,1000),
            'sku'   => rand(1000,100000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1

        ];
    }
}
