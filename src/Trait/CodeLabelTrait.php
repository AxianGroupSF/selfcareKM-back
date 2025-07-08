<?php
namespace App\Trait;

use App\Constante\SelfcareConst;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CodeLabelTrait
{
    #[Groups([SelfcareConst::USER_READ, SelfcareConst::ROLE_READ])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $code;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $label;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }
}
