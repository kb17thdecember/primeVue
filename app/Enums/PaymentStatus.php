<?php

namespace App\Enums;

enum PaymentStatus: int
{
    use EnumTrait;

    case SUCCESS = 1;
    case CANCEL = 2;
    case PENDING = 3;
    case SYSTEM_CANCEL = 4;

    public static function stringNames(): array
    {
        return [
            self::SUCCESS->value => 'Success',
            self::CANCEL->value => 'Failed',
            self::PENDING->value => 'Pending',
            self::SYSTEM_CANCEL->value => 'System cancel',
        ];
    }

    public function string(): string
    {
        $stringNames = $this->stringNames();

        return $stringNames[$this->value];
    }
}
