<?php

namespace App\Enums;

enum TokenStockStatus: int
{
    use EnumTrait;

    case ADDED = 1;
    case NO_ADDED = 2;

    public static function stringNames(): array
    {
        return [
            self::ADDED->value => 'Added',
            self::NO_ADDED->value => 'No added',
        ];
    }

    public function string(): string
    {
        $stringNames = $this->stringNames();

        return $stringNames[$this->value];
    }
}
