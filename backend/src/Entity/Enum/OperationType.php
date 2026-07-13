<?php

namespace App\Entity\Enum;

enum OperationType: string
{
    case GAIN_RESERVATION = 'gain_reservation';
    case ECHANGE_RECOMPENSE = 'echange_recompense';
}
