<?php

namespace App\Enums;

enum ShopStatus: int
{
    use EnumTrait;

    case ACTIVE = 1;
    case INACTIVE = 2;

    public static function stringNames(): array
    {
        return [
            self::ACTIVE->value => 'Active',
            self::INACTIVE->value => 'InActive',
        ];
    }

    public function string(): string
    {
        $stringNames = $this->stringNames();

        return $stringNames[$this->value];
    }
}
