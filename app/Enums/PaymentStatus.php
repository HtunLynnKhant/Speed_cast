<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = 0;
    case IN_PROCESS = 1;
    case COMPLETED = 2;
}
