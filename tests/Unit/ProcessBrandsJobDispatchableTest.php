<?php

namespace Tests\Unit;

use App\Jobs\ProcessBrands;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ProcessBrandsJobDispatchableTest extends TestCase
{
    public function test_process_brands_job_is_dispatchable()
    {
        Bus::fake();
        $fakeData = [
            1 => [
                [
                    'codigo' => 1,
                    'nome' => 'Teste',
                ],
            ],
        ];

        ProcessBrands::dispatch($fakeData);
        Bus::assertDispatched(ProcessBrands::class);
    }
}
