<?php

namespace App\Jobs;

use App\Models\Brand;
use Generator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessBrands implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $brandsByType) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->iterateBrands($this->brandsByType) as $brand) {
            $existingBrand = Brand::where('vehicle_type_id', $brand->vehicle_type_id)
                ->where('brand_code', $brand->brand_code)
                ->first();

            if ($existingBrand) {
                if ($existingBrand->processed) {
                    continue;
                }

                $brand = $existingBrand;
            } else {
                $brand->save();
            }

            FetchModels::dispatch($brand);
        }
    }

    /**
     * Iteração das marcas
     *
     * @return Generator|Brand[]
     */
    private function iterateBrands($brandsByType): Generator
    {
        foreach ($brandsByType as $typeId => $brands) {
            foreach ($brands as $brand) {
                $brandModel = new Brand([
                    'vehicle_type_id' => $typeId,
                    'brand_code' => $brand['codigo'],
                    'brand_name' => $brand['nome'],
                    'processed' => 0,
                ]);
                yield $brandModel;
            }
        }
    }
}
