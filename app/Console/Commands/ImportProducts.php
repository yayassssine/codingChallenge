<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use App\Services\ValidationService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class ImportProducts extends Command
{
    protected $signature = 'product:create
                            {name : The name of the product}
                            {price : The price of the product}
                            {category_id : The ID of the category}
                            {description? : The description of the product}';

    protected $description = 'Create a new product from the CLI';

    private ProductService $productService;
    private ValidationService $validationService;

    public function __construct(ProductService $productService, ValidationService $validationService)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->validationService = $validationService;
    }

    public function handle(): int
    {
        $data = [
            'name' => $this->argument('name'),
            'description' => $this->argument('description'),
            'price' => $this->argument('price'),
            'categories' => [$this->argument('category_id')],
        ];

        $isSuccess = false;
        try {
            $this->validationService->validateProduct($data);
            $product = $this->productService->createProduct($data);

            if ($product) {
                $this->productService->syncCategories($product, $data['categories']);

                $this->info('Product created successfully!');
                $isSuccess = true;
            }
        } catch (ValidationException $e) {
            foreach ($e->errors() as $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $this->error($error);
                }
            }
        }

        return $isSuccess ? 0 : 1;
    }
}
