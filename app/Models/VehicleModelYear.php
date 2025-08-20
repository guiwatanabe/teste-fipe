<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelYear extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleModelYearFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'vehicle_model_id',
        'model_year_code',
        'model_year_name',
        'model_year_notes',
        'update_user',
    ];
}
