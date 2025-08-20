<?php

namespace App\Models;

use App\Models\Scopes\VehicleModelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleModel extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleModelFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'brand_id',
        'model_code',
        'model_name',
        'model_notes',
        'processed',
    ];

    public static array $filterable = [
        'tipoVeiculo' => [
            'column' => 'vehicle_type_id',
            'method' => 'where',
            'validation' => 'sometimes|integer|between:1,3',
        ],
        'codigo' => [
            'column' => 'model_code',
            'method' => 'where',
            'validation' => 'sometimes|string|max:255',
        ],
        'nome' => [
            'column' => 'model_name',
            'method' => 'like',
            'validation' => 'sometimes|string|max:255',
        ],
    ];

    protected static function booted()
    {
        static::addGlobalScope(new VehicleModelScope);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
