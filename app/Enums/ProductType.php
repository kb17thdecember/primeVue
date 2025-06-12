<?php

namespace App\Enums;

enum ProductType: int
{
    use EnumTrait;

    case ONE_TIME = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;
    case YEARLY = 4;
    case EVERY_THREE_MONTH = 5;
    case EVERY_SIX_MONTH = 6;

    public static function stringNames(): array
    {
        return [
            self::ONE_TIME->value => 'One Time',
            self::WEEKLY->value => 'Weekly',
            self::MONTHLY->value => 'Monthly',
            self::YEARLY->value => 'Yearly',
            self::EVERY_THREE_MONTH->value => 'Every Three Months',
            self::EVERY_SIX_MONTH->value => 'Every Six Months'
        ];
    }

    public function string(): string
    {
        $stringNames = $this->stringNames();

        return $stringNames[$this->value];
    }

    public static function options(): array
    {
        return [
            [
                'name' => self::stringNames()[self::ONE_TIME->value],
                'value' => self::ONE_TIME->value,
            ],
            [
                'name' => self::stringNames()[self::WEEKLY->value],
                'value' => self::WEEKLY->value,
            ],
            [
                'name' => self::stringNames()[self::MONTHLY->value],
                'value' => self::MONTHLY->value,
            ],
            [
                'name' => self::stringNames()[self::YEARLY->value],
                'value' => self::YEARLY->value,
            ],
            [
                'name' => self::stringNames()[self::EVERY_THREE_MONTH->value],
                'value' => self::EVERY_THREE_MONTH->value,
            ],
            [
                'name' => self::stringNames()[self::EVERY_SIX_MONTH->value],
                'value' => self::EVERY_SIX_MONTH->value,
            ]
        ];
    }
}
