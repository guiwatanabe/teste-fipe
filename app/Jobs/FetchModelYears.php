<?php

namespace App\Jobs;

use App\Enums\VehicleType;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\VehicleModelYear;
use App\Services\Fipe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchModelYears implements ShouldQueue
{
    use Queueable;

    private $fipe;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Brand $brand, protected VehicleModel $vehicleModel)
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
        $modelCode = $this->vehicleModel->model_code;

        $yearsForModel = $this->fipe->fetchYearsByTypeBrandAndModel($vehicleType, $brandCode, $modelCode);
        if (! $yearsForModel) {
            $this->fail();
        }

        foreach ($yearsForModel as $year) {
            VehicleModelYear::firstOrCreate(
                [
                    'vehicle_model_id' => $this->vehicleModel->id,
                    'model_year_code' => $year['codigo'],
                ],
                [
                    'model_year_name' => $year['nome'],
                    'model_year_notes' => null,
                    'update_user' => null, // TODO: id do user autenticado
                ]
            );

            $this->vehicleModel->processed = true;
            $this->vehicleModel->save();
        }
    }
}
