<?php

namespace App\Entity\Enum;

enum RecompenseType: string
{
    case REDUCTION = 'reduction';
    case BOISSON = 'boisson';
    case DESSERT = 'dessert';
    case AUTRE = 'autre';
}
