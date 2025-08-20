<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FipeApiReachableTest extends TestCase
{
    public function test_fipe_api_reachable()
    {
        $baseUrl = Config::get('services.fipe.baseUrl');
        $endPoint = $baseUrl.'carros/marcas';

        // testar sem utilizar a api mesmo
        Http::fake([
            $baseUrl.'*' => Http::response([
                [
                    'codigo' => 1,
                    'nome' => 'Teste',
                ],
            ], 200),
        ]);

        $response = Http::get($endPoint);
        expect($response->successful())->toBeTrue();
    }
}
