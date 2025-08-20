<?php

namespace Tests\Unit;

use App\Jobs\ProcessBrands;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProcessBrandsJobProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_brands_job_is_processing()
    {
        $baseUrl = Config::get('services.fipe.baseUrl');

        // testar sem utilizar a api mesmo
        Http::fake([
            $baseUrl.'*' => Http::response([
                'anos' => [
                    [
                        'codigo' => '2000',
                        'nome' => '2000',
                    ],
                ],
                'modelos' => [
                    [
                        'codigo' => 1,
                        'nome' => 'Teste',
                    ],
                ],
            ], 200),
        ]);

        $fakeData = [
            1 => [
                [
                    'codigo' => 1,
                    'nome' => 'Teste',
                ],
            ],
        ];

        $job = new ProcessBrands($fakeData);
        $job->handle();

        $this->assertDatabaseHas('brands', ['brand_code' => '1', 'brand_name' => 'Teste']);
    }
}
