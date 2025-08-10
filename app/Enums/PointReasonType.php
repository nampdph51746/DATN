<?php

namespace App\Enums;

enum PointReasonType: string
{
    case Earned = 'earned';
    case Spent = 'spent';
    case Expired = 'expired';
}