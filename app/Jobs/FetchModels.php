<?php

namespace App\Jobs;

use App\Enums\VehicleType;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Services\Fipe;
use Generator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchModels implements ShouldQueue
{
    use Queueable;

    private $fipe;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Brand $brand)
    {
        $this->fipe = new Fipe;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $vehicleType = VehicleType::from($this->brand->vehicle_type_id);
        $brandCode = $this->brand->brand_code;

        $models = $this->fipe->fetchModelsByTypeAndBrand($vehicleType, $brandCode);
        if (! $models) {
            $this->fail();
        }

        foreach ($this->iterateModels($models) as $model) {
            $vehicleModel = VehicleModel::firstOrCreate(
                [
                    'brand_id' => $this->brand->id,
                    'model_code' => $model->model_code,
                ],
                [
                    'model_name' => $model->model_name,
                    'processed' => false,
                ]
            );

            $this->brand->processed = true;
            $this->brand->save();

            // Se for necessário buscar também os anos para salvar
            // FetchModelYears::dispatch($this->brand, $vehicleModel);
        }
    }

    /**
     * Iteração das marcas
     */
    private function iterateModels($vehicleModels): Generator
    {
        foreach ($vehicleModels as $model) {
            $vehicleModel = new VehicleModel([
                'brand_id' => $this->brand->id,
                'model_code' => $model['codigo'],
                'model_name' => $model['nome'],
                'processed' => true,
            ]);
            yield $vehicleModel;
        }
    }
}
