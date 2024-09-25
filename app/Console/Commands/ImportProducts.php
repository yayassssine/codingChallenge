<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:create
                            {name : name of product}
                            {price : price}
                            {category_id : ID of category}
                            {description? : description of product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new product from the CLI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $description = $this->argument('description');
        $price = $this->argument('price');
        $categoryId = $this->argument('category_id');

        $product = Product::create([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $categoryId,
        ]);

        if ($product) {
            $this->info('Product created successfully!');
        } else {
            $this->error('Failed to create the product.');
        }

        return 0;
    }

}
