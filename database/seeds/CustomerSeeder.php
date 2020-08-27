<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds=[
            ['name'=>'customer 1', 'description'=>'customer 1 description', 'rating'=>5], 
            ['name'=>'customer 2', 'description'=>'customer 2 description', 'rating'=>4], 
            ['name'=>'customer 3', 'description'=>'customer 3 description', 'rating'=>2], 
            ['name'=>'customer 4', 'description'=>'customer 4 description', 'rating'=>3], 
            ['name'=>'customer 5', 'description'=>'customer 5 description', 'rating'=>1]];
        
        foreach($seeds as $seed)
        {
            \App\Models\Customer::create($seed);
        }
    }
}
