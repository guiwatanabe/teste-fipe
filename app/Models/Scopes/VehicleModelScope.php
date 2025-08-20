<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VehicleModelScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->join('brands', 'brands.id', '=', 'vehicle_models.brand_id')
            ->select([
                'vehicle_models.*',
                'brands.brand_code',
                'brands.brand_name',
                'brands.vehicle_type_id',
            ]);
    }
}
