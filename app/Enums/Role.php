<?php

namespace App\Enums;

enum Role: int
{
    use EnumTrait;

    case ADMIN = 0;
    case SHOP = 1;
}
