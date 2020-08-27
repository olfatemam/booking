<?php

use Illuminate\Database\Seeder;

class CleanerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds=[
            ['name'=>'cleaner 1', 'description'=>'cleaner 1 description', 'rating'=>3], 
            ['name'=>'cleaner 2', 'description'=>'cleaner 2 description', 'rating'=>2], 
            ['name'=>'cleaner 3', 'description'=>'cleaner 3 description', 'rating'=>3], 
            ['name'=>'cleaner 4', 'description'=>'cleaner 4 description', 'rating'=>4], 
            ['name'=>'cleaner 5', 'description'=>'cleaner 5 description', 'rating'=>5]];
        
        foreach($seeds as $seed)
        {
            \App\Models\Cleaner::create($seed);
        }
    }
}
