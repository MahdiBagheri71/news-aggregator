<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait BaseEnum
{
    public static function getSelectBoxFilamentItems(): Collection
    {
        return collect(self::cases())
            ->flatMap(function ($item) {
                return [$item->value => $item->getLabel()];
            });
    }

    public static function getSelectBoxTransformItems(): Collection
    {
        return collect(self::cases())
            ->map(function ($item) {
                return ['key' => $item->getLabel(), 'value' => $item->value];
            });
    }

    protected function getLabel(): string
    {
        return __($this->name);
    }

    public static function getWithLabel(): Collection
    {
        return collect(self::cases())
            ->map(function ($item) {
                return ['label' => $item->getLabel(), 'value' => $item->value, 'name' => $item->name];
            });
    }

    public static function getAllValues(): Collection
    {
        return collect(self::cases())->pluck('value');
    }

    public static function getAllLabels(): Collection
    {
        return collect(self::cases())
            ->map(function ($item) {
                return $item->getLabel();
            });
    }

    public static function getAllNames(): Collection
    {
        return collect(self::cases())->pluck('name');
    }
}
