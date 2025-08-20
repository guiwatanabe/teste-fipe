<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use App\Services\QueryFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ModelController extends Controller
{
    public function __construct(
        protected QueryFilterService $queryFilterService
    ) {}

    public function models(Request $request, string $idMarca)
    {
        $cacheKey = 'models:'.$idMarca.':'.md5(http_build_query($request->query()));

        return Cache::remember($cacheKey, 300, function () use ($request, $idMarca) {
            $rules = collect(VehicleModel::$filterable)
                ->mapWithKeys(fn ($filter, $key) => [$key => $filter['validation']])
                ->toArray();

            $validated = $request->validate($rules);

            $query = VehicleModel::with('brand')->where('brands.brand_code', $idMarca);
            $query = $this->queryFilterService->applyFilters($query, $validated, VehicleModel::$filterable);

            return $query->paginate()->toResourceCollection();
        });
    }

    public function updateModel(Request $request, string $idMarca, string $idModelo)
    {
        $request->validate([
            'nome' => 'sometimes|string|max:255',
            'observacoes' => 'sometimes|string|max:65535',
        ]);

        $vehicleModel = VehicleModel::with('brand')->where('brands.brand_code', $idMarca)->where('model_code', $idModelo)->first();

        if (! $vehicleModel) {
            return response()->json([
                'status' => 404,
                'error' => 'Não foi possível encontrar o modelo especificado.',
            ])->setStatusCode(404);
        }

        $vehicleModel->update([
            'model_name' => $request->input('nome', $vehicleModel->model_name),
            'model_notes' => $request->input('observacoes', $vehicleModel->model_notes),
        ]);

        Cache::forget('model:'.$idModelo);
        Cache::forget('brand:'.$idMarca);

        return response()->json([
            'status' => 200,
            'message' => 'Dados atualizados com sucesso.',
            'data' => $vehicleModel->fresh('brand'),
        ]);
    }
}
