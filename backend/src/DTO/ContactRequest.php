<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public string $prenom = '';

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public string $nom = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(max: 2000)]
    public string $message = '';
}
