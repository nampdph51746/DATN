<?php

namespace App\Enums;

enum ProductStatus: string {
    case Valid = 'valid';
    case Checked = 'checked';
    case Used = 'used';
    case Cancelled = 'cancelled';
}