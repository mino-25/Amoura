<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationRequest
{
    #[Assert\NotBlank]
    #[Assert\Date]
    public string $date = '';

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $creneauId = 0;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 20)]
    public int $nbCouverts = 1;
}
