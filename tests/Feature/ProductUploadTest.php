<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $this->seller = User::factory()->create([
        'role' => 'seller',
    ]);

    $this->otherSeller = User::factory()->create([
        'role' => 'seller',
    ]);

    $this->buyer = User::factory()->create([
        'role' => 'user',
    ]);

    $this->category = Category::firstOrCreate(['name' => 'Headphones']);
});

test('seller can create a product with an uploaded image via form data', function () {
    $image = UploadedFile::fake()->create('headphone.jpg', 1024, 'image/jpeg');

    $response = $this->actingAs($this->seller)
        ->post('/api/v1/product', [
            'name' => 'Wireless Headphone X',
            'category' => 'Headphones',
            'price' => 250000.00,
            'stock' => 10,
            'description' => 'A great headphone',
            'image' => $image,
        ]);

    $response->assertStatus(201);
    $response->assertJsonPath('success', true);

    $product = Product::where('name', 'Wireless Headphone X')->first();
    expect($product)->not->toBeNull();
    expect($product->image)->not->toBeNull();
    expect($product->image)->toContain('/storage/products/');

    $filename = basename(parse_url($product->image, PHP_URL_PATH));
    Storage::disk('public')->assertExists('products/'.$filename);
});

test('seller can create a product without an image via form data', function () {
    $response = $this->actingAs($this->seller)
        ->post('/api/v1/product', [
            'name' => 'Product Without Image',
            'category' => 'Headphones',
            'price' => 100000.00,
            'stock' => 5,
            'description' => 'Simple product',
        ]);

    $response->assertStatus(201);
    $product = Product::where('name', 'Product Without Image')->first();
    expect($product)->not->toBeNull();
    expect($product->image)->toBeNull();
});

test('cannot create product with image exceeding 2mb', function () {
    $largeImage = UploadedFile::fake()->create('large.png', 2500, 'image/png');

    $response = $this->actingAs($this->seller)
        ->post('/api/v1/product', [
            'name' => 'Too Large Image Product',
            'category' => 'Headphones',
            'price' => 50000.00,
            'image' => $largeImage,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['image']);
});

test('cannot create product with invalid image format', function () {
    $textDoc = UploadedFile::fake()->create('doc.pdf', 500, 'application/pdf');

    $response = $this->actingAs($this->seller)
        ->post('/api/v1/product', [
            'name' => 'Invalid Format Product',
            'category' => 'Headphones',
            'price' => 50000.00,
            'image' => $textDoc,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['image']);

    $gifImage = UploadedFile::fake()->create('anim.gif', 500, 'image/gif');

    $responseGif = $this->actingAs($this->seller)
        ->post('/api/v1/product', [
            'name' => 'Gif Product',
            'category' => 'Headphones',
            'price' => 50000.00,
            'image' => $gifImage,
        ]);

    $responseGif->assertStatus(422);
    $responseGif->assertJsonValidationErrors(['image']);
});

test('seller can update product photo via form data', function () {
    $oldFile = UploadedFile::fake()->create('old.jpg', 500, 'image/jpeg');
    $oldPath = $oldFile->store('products', 'public');
    $oldUrl = url('storage/'.$oldPath);

    $product = Product::create([
        'name' => 'Existing Product',
        'user_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'price' => 150000,
        'stock' => 10,
        'image' => $oldUrl,
    ]);

    Storage::disk('public')->assertExists($oldPath);

    $newImage = UploadedFile::fake()->create('new.png', 800, 'image/png');

    $response = $this->actingAs($this->seller)
        ->post("/api/v1/product/{$product->id}", [
            'image' => $newImage,
        ]);

    $response->assertStatus(200);

    $product->refresh();
    expect($product->image)->not->toBe($oldUrl);

    // Old image should be deleted
    Storage::disk('public')->assertMissing($oldPath);

    // New image should exist
    $newFilename = basename(parse_url($product->image, PHP_URL_PATH));
    Storage::disk('public')->assertExists('products/'.$newFilename);
});

test('updating product without image preserves existing image', function () {
    $file = UploadedFile::fake()->create('photo.png', 500, 'image/png');
    $path = $file->store('products', 'public');
    $url = url('storage/'.$path);

    $product = Product::create([
        'name' => 'Photo Product',
        'user_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'price' => 150000,
        'stock' => 10,
        'image' => $url,
    ]);

    $response = $this->actingAs($this->seller)
        ->post("/api/v1/product/{$product->id}", [
            'name' => 'Renamed Product',
            'price' => 175000,
        ]);

    $response->assertStatus(200);

    $product->refresh();
    expect($product->name)->toBe('Renamed Product');
    expect($product->image)->toBe($url);
    Storage::disk('public')->assertExists($path);
});

test('another seller cannot update product', function () {
    $product = Product::create([
        'name' => 'Owner Product',
        'user_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'price' => 150000,
        'stock' => 10,
    ]);

    $response = $this->actingAs($this->otherSeller)
        ->post("/api/v1/product/{$product->id}", [
            'name' => 'Hacked Product',
        ]);

    $response->assertStatus(403);
});

test('seller can update product photo via method spoofing put and patch', function () {
    $product = Product::create([
        'name' => 'Spoof Product',
        'user_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'price' => 150000,
        'stock' => 10,
    ]);

    $newImage = UploadedFile::fake()->create('updated.png', 800, 'image/png');

    $response = $this->actingAs($this->seller)
        ->post("/api/v1/product/{$product->id}", [
            '_method' => 'PUT',
            'image' => $newImage,
        ]);

    $response->assertStatus(200);
    $product->refresh();
    expect($product->image)->not->toBeNull();
});

test('buyer cannot create product', function () {
    $response = $this->actingAs($this->buyer)
        ->post('/api/v1/product', [
            'name' => 'Buyer Product',
            'category' => 'Headphones',
            'price' => 50000,
        ]);

    $response->assertStatus(403);
});

test('deleting a product deletes its stored image', function () {
    $file = UploadedFile::fake()->create('to_delete.jpg', 500, 'image/jpeg');
    $path = $file->store('products', 'public');
    $url = url('storage/'.$path);

    $product = Product::create([
        'name' => 'Product To Delete',
        'user_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'price' => 50000,
        'stock' => 2,
        'image' => $url,
    ]);

    Storage::disk('public')->assertExists($path);

    $response = $this->actingAs($this->seller)
        ->delete("/api/v1/product/{$product->id}");

    $response->assertStatus(204);
    Storage::disk('public')->assertMissing($path);
});
