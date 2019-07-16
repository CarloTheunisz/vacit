<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SollicitatieRepository")
 */
class Sollicitatie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacature", inversedBy="sollicitaties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacature;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sollicitaties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datum;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $uitgenodigd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVacature(): ?Vacature
    {
        return $this->vacature;
    }

    public function setVacature(?Vacature $vacature): self
    {
        $this->vacature = $vacature;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): self
    {
        $this->datum = $datum;

        return $this;
    }

    public function getUitgenodigd(): ?bool
    {
        return $this->uitgenodigd;
    }

    public function setUitgenodigd(?bool $uitgenodigd): self
    {
        $this->uitgenodigd = $uitgenodigd;

        return $this;
    }
}
