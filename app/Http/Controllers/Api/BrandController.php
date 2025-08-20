<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessBrands;
use App\Models\Brand;
use App\Services\Fipe;
use App\Services\QueryFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function __construct(
        protected Fipe $fipe,
        protected QueryFilterService $queryFilterService
    ) {}

    public function initialize()
    {
        $brandsByType = $this->fipe->fetchAllBrands();

        if ($brandsByType === false) {
            return response()->json([
                'status' => 400,
                'error' => 'Não foi possível consultar as marcas, tente novamente mais tarde.',
            ])->setStatusCode(400);
        }

        ProcessBrands::dispatch($brandsByType);

        return response()->json([
            'status' => 200,
            'message' => 'Marcas enviadas para a fila de processamento. Em breve os resultados poderão ser consultados.',
        ]);
    }

    public function brands(Request $request)
    {
        $cacheKey = 'brands:'.md5(http_build_query($request->query()));

        return Cache::remember($cacheKey, 300, function () use ($request) {
            $rules = collect(Brand::$filterable)
                ->mapWithKeys(fn ($filter, $key) => [$key => $filter['validation']])
                ->toArray();

            $validated = $request->validate($rules);

            $query = Brand::query();
            $query = $this->queryFilterService->applyFilters($query, $validated, Brand::$filterable);

            return $query->paginate()->toResourceCollection();
        });
    }

    public function brand(Request $request, string $idMarca)
    {
        $cacheKey = 'brand:'.$idMarca;
        $brand = Cache::remember($cacheKey, 300, function () use ($idMarca) {
            return Brand::where('brand_code', $idMarca)->first();
        });

        if (! $brand) {
            return response()->json(['status' => 404, 'error' => 'Brand not found.'])->setStatusCode(404);
        }

        return $brand->toResource();
    }
}
