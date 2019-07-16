<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReferentieRepository")
 */
class Referentie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $domein;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $omschrijving;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomein(): ?string
    {
        return $this->domein;
    }

    public function setDomein(string $domein): self
    {
        $this->domein = $domein;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getOmschrijving(): ?string
    {
        return $this->omschrijving;
    }

    public function setOmschrijving(string $omschrijving): self
    {
        $this->omschrijving = $omschrijving;

        return $this;
    }
}
