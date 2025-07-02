<?php

namespace App\Entity;

use App\Trait\CodeLabelTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RightRepository;

#[ORM\Entity(repositoryClass: RightRepository::class)]
#[ORM\Table(name: '`right`')]
class Right
{
    use CodeLabelTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
