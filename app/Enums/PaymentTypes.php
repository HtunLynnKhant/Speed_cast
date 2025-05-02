<?php

namespace App\Enums;

enum PaymentTypes: int
{
    case FULL = 1;
    case PARTIAL = 2;
}
