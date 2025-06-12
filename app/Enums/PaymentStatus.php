<?php

namespace App\Enums;

enum PaymentStatus: int
{
    use EnumTrait;

    case SUCCESS = 1;
    case FAILED = 2;

    public static function stringNames(): array
    {
        return [
            self::SUCCESS->value => 'Success',
            self::FAILED->value => 'Failed',
        ];
    }

    public function string(): string
    {
        $stringNames = $this->stringNames();

        return $stringNames[$this->value];
    }
}
