<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    /** @use HasFactory<\Database\Factories\BrandFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'vehicle_type_id',
        'brand_code',
        'brand_name',
        'processed',
    ];

    public static array $filterable = [
        'tipoVeiculo' => [
            'column' => 'vehicle_type_id',
            'method' => 'where',
            'validation' => 'sometimes|integer|between:1,3',
        ],
        'codigo' => [
            'column' => 'brand_code',
            'method' => 'where',
            'validation' => 'sometimes|integer',
        ],
        'nome' => [
            'column' => 'brand_name',
            'method' => 'like',
            'validation' => 'sometimes|string|max:255',
        ],
    ];

    public function vehicleModels(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}
