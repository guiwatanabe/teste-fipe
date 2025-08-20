<?php

namespace App\Http\Resources;

use App\Enums\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $brand_code
 * @property string $brand_name
 * @property int $vehicle_type_id
 */
class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->brand_code,
            'nome' => $this->brand_name,
            'idTipoVeiculo' => $this->vehicle_type_id,
            'tipoVeiculo' => VehicleType::getLabelByID($this->vehicle_type_id),
        ];
    }
}
