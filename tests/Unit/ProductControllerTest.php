<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethodDisplaysProductsAndCategories()
    {
        $categories = Category::factory()->count(3)->create();
        $products = Product::factory()->count(5)->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('products');
        $response->assertViewHas('categories');
    }

    public function testCreateMethodDisplaysFormWithCategories()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    public function testStoreMethodCreatesProductWithImageAndCategories()
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->post(route('products.store'), [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'categories' => [$category->id],
            'image' => $file,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);

        Storage::disk('public')->assertExists('images/' . $file->hashName());

        $product = Product::where('name', 'Test Product')->first();
        $this->assertTrue($product->categories->contains($category));
    }
    public function testStoreMethodValidationFails()
    {
        $response = $this->post(route('products.store'), [
            'name' => '',
            'price' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'price']);
    }
}
