<?php

namespace App\Entity\Enum;

enum ReservationStatut: string
{
    case EN_ATTENTE = 'en_attente';
    case CONFIRMEE = 'confirmee';
    case ANNULEE = 'annulee';
}
