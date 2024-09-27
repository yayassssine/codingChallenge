<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

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
        $data = [
            'name' => $this->argument('name'),
            'description' => $this->argument('description'),
            'price' => $this->argument('price'),
            'category_id' => $this->argument('category_id'),
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return 1;
        }

        $product = Product::create($data);

        if ($product) {
            $this->info('Product created successfully!');
        } else {
            $this->error('Failed to create the product.');
        }

        return 0;
    }
}
