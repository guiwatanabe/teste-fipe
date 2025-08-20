<?php

namespace App\Services;

use App\Enums\VehicleType;
use Illuminate\Support\Facades\Http;

class Fipe
{
    /** @var string BaseURL Serviço FIPE */
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.fipe.baseUrl');
    }

    /**
     * Busca todas as marcas disponíveis para o tipo de veículo informado.
     *
     * @param  VehicleType  $vehicleType  Tipo de veículo
     * @return array|false Retorna um array com as marcas, ou false se falhar.
     */
    public function fetchBrandsByVehicleType(VehicleType $vehicleType): array|false
    {
        $vehicleTypeName = $vehicleType->label();
        $endPoint = $this->baseUrl."$vehicleTypeName/marcas";
        $response = Http::get($endPoint);

        if (! $response->successful()) {
            return false;
        }

        return $response->json();
    }

    /**
     * Busca todas as marcas disponíveis para todos os tipos de veículos.
     *
     * @return array|false Retorna um array com as marcas agrupadas em tipo, ou false se falhar.
     */
    public function fetchAllBrands(): array|false
    {
        $vehicleTypes = VehicleType::cases();
        $allBrands = [];

        foreach ($vehicleTypes as $vehicleType) {
            $brands = $this->fetchBrandsByVehicleType($vehicleType);

            if ($brands === false) {
                return false;
            }

            $allBrands[$vehicleType->value] = $brands;
        }

        return $allBrands;
    }

    /**
     * Busca de anos e modelos a partir de um tipo de veículo e marca
     *
     * @return array|false Retorna um array de anos e um array de modelos, ou false se falhar
     */
    public function fetchModelsAndYearsByTypeAndBrand(VehicleType $vehicleType, int $brandCode): array|false
    {
        $vehicleTypeName = $vehicleType->label();
        $endPoint = $this->baseUrl."$vehicleTypeName/marcas/$brandCode/modelos";
        $response = Http::get($endPoint);

        if (! $response->successful()) {
            return false;
        }

        return $response->json();
    }

    /**
     * Busca os modelos a partir de um tipo de veículo e marca
     *
     * @return array|false Retorna um array de modelos, ou false se falhar
     */
    public function fetchModelsByTypeAndBrand(VehicleType $vehicleType, int $brandCode): array|false
    {
        $modelsAndYears = $this->fetchModelsAndYearsByTypeAndBrand($vehicleType, $brandCode);
        if (! $modelsAndYears) {
            return false;
        }

        return $modelsAndYears['modelos'];
    }

    /**
     * Busca os anos a partir de um tipo de veículo e marca
     *
     * @return array|false Retorna um array de anos, ou false se falhar
     */
    public function fetchYearsByTypeAndBrand(VehicleType $vehicleType, int $brandCode): array|false
    {
        $modelsAndYears = $this->fetchModelsAndYearsByTypeAndBrand($vehicleType, $brandCode);
        if (! $modelsAndYears) {
            return false;
        }

        return $modelsAndYears['years'];
    }

    /**
     * Busca os anos a partir de um tipo de veículo, marca e código do veículo
     *
     * @return array|false Retorna um array de anos, ou false se falhar
     */
    public function fetchYearsByTypeBrandAndModel(VehicleType $vehicleType, int $brandCode, int $vehicleCode): array|false
    {
        $vehicleTypeName = $vehicleType->label();
        $endPoint = $this->baseUrl."$vehicleTypeName/marcas/$brandCode/modelos/$vehicleCode/anos";
        $response = Http::get($endPoint);

        if (! $response->successful()) {
            return false;
        }

        return $response->json();
    }
}
