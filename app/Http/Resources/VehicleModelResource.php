<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Brand $brand
 * @property string $model_code
 * @property string $model_name
 * @property string $model_notes
 */
class VehicleModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => $this->model_code,
            'nome' => $this->model_name,
            'observacoes' => $this->model_notes,
            'marca' => $this->brand->toResource(),
        ];
    }
}
