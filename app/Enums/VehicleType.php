<?php

namespace App\Enums;

enum VehicleType: int
{
    case CAR = 1;
    case MOTORCYCLE = 2;
    case TRUCK = 3;

    public function label(): string
    {
        return match ($this) {
            self::CAR => 'carros',
            self::MOTORCYCLE => 'motos',
            self::TRUCK => 'caminhoes',
        };
    }

    public static function getAllIDs(): array
    {
        return array_map(fn ($type) => $type->value, self::cases());
    }

    public static function getAllLabels(): array
    {
        return array_map(fn ($type) => $type->label(), self::cases());
    }

    public static function getLabelByID(int $id): string
    {
        $type = self::tryFrom($id);

        return $type?->label() ?? self::CAR->label();
    }
}
