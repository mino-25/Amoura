<?php

namespace App\Entity\Enum;

enum UserRole: string
{
    case CLIENT = 'client';
    case ADMIN = 'admin';
}
