<?php

use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('test cache miss', function () {
    Brand::factory()->create(['brand_code' => 1, 'brand_name' => 'Teste', 'vehicle_type_id' => 1]);

    $expectedCacheKey = 'brands:'.md5(http_build_query([]));

    $this->assertFalse(Cache::has($expectedCacheKey));

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->getJson('/api/marcas');

    $response->assertStatus(200);
    $response->assertJsonFragment(['codigo' => 1, 'idTipoVeiculo' => 1, 'nome' => 'Teste']);

    $this->assertTrue(Cache::has($expectedCacheKey));
});

test('test cache hit', function () {
    Brand::factory()->create(['brand_code' => 1, 'brand_name' => 'D Teste']);

    $cacheKey = 'brands:'.md5(http_build_query([]));

    $cachedData = [
        'data' => [
            ['codigo' => '999', 'nome' => 'Teste C'],
        ],
    ];
    Cache::put($cacheKey, $cachedData, 300);

    $this->assertTrue(Cache::has($cacheKey));

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->getJson('/api/marcas');

    $response->assertStatus(200);
    $response->assertJsonFragment(['data' => [['codigo' => '999', 'nome' => 'Teste C']]]);
    $response->assertJsonMissing(['nome' => 'D Teste']);

    $this->assertTrue(Cache::has($cacheKey));
});
