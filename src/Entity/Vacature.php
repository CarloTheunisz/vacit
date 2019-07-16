<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacatureRepository")
 */
class Vacature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="vacatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referentie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referentie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plaats;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $titel;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icoon;

    /**
     * @ORM\Column(type="string", length=1023)
     */
    private $omschrijving;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sollicitatie", mappedBy="vacature", orphanRemoval=true)
     */
    private $sollicitaties;

    public function __construct()
    {
        $this->sollicitaties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNiveau(): ?Referentie
    {
        return $this->niveau;
    }

    public function setNiveau(?Referentie $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getPlaats(): ?Referentie
    {
        return $this->plaats;
    }

    public function setPlaats(?Referentie $plaats): self
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function getTitel(): ?string
    {
        return $this->titel;
    }

    public function setTitel(string $titel): self
    {
        $this->titel = $titel;

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

    public function getIcoon(): ?string
    {
        return $this->icoon;
    }

    public function setIcoon(?string $icoon): self
    {
        $this->icoon = $icoon;

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

    /**
     * @return Collection|Sollicitatie[]
     */
    public function getSollicitaties(): Collection
    {
        return $this->sollicitaties;
    }

    public function addSollicitaty(Sollicitatie $sollicitaty): self
    {
        if (!$this->sollicitaties->contains($sollicitaty)) {
            $this->sollicitaties[] = $sollicitaty;
            $sollicitaty->setVacature($this);
        }

        return $this;
    }

    public function removeSollicitaty(Sollicitatie $sollicitaty): self
    {
        if ($this->sollicitaties->contains($sollicitaty)) {
            $this->sollicitaties->removeElement($sollicitaty);
            // set the owning side to null (unless already changed)
            if ($sollicitaty->getVacature() === $this) {
                $sollicitaty->setVacature(null);
            }
        }

        return $this;
    }
}
