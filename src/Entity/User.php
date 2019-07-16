<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Referentie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $plaats;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $voornaam;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $achternaam;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telefoonnummer;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $adres;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=1023, nullable=true)
     */
    private $omschrijving;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $afbeelding;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vacature", mappedBy="user", orphanRemoval=true)
     */
    private $vacatures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sollicitatie", mappedBy="user", orphanRemoval=true)
     */
    private $sollicitaties;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $contactpersoon;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $geboortedatum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

    public function __construct() {
        parent::__construct();
        $this->vacatures = new ArrayCollection();
        $this->sollicitaties = new ArrayCollection();
        $this->addRole('ROLE_CANDIDATE');
        // your own logic
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

    public function getVoornaam(): ?string
    {
        return $this->voornaam;
    }

    public function setVoornaam(string $voornaam): self
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function getAchternaam(): ?string
    {
        return $this->achternaam;
    }

    public function setAchternaam(?string $achternaam): self
    {
        $this->achternaam = $achternaam;

        return $this;
    }

    public function getTelefoonnummer(): ?string
    {
        return $this->telefoonnummer;
    }

    public function setTelefoonnummer(string $telefoonnummer): self
    {
        $this->telefoonnummer = $telefoonnummer;

        return $this;
    }

    public function getAdres(): ?string
    {
        return $this->adres;
    }

    public function setAdres(string $adres): self
    {
        $this->adres = $adres;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

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

    public function getAfbeelding(): ?string
    {
        return $this->afbeelding;
    }

    public function setAfbeelding(?string $afbeelding): self
    {
        $this->afbeelding = $afbeelding;

        return $this;
    }

    /**
     * @return Collection|Vacature[]
     */
    public function getVacatures(): Collection
    {
        return $this->vacatures;
    }

    public function addVacature(Vacature $vacature): self
    {
        if (!$this->vacatures->contains($vacature)) {
            $this->vacatures[] = $vacature;
            $vacature->setUser($this);
        }

        return $this;
    }

    public function removeVacature(Vacature $vacature): self
    {
        if ($this->vacatures->contains($vacature)) {
            $this->vacatures->removeElement($vacature);
            // set the owning side to null (unless already changed)
            if ($vacature->getUser() === $this) {
                $vacature->setUser(null);
            }
        }

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
            $sollicitaty->setUser($this);
        }

        return $this;
    }

    public function removeSollicitaty(Sollicitatie $sollicitaty): self
    {
        if ($this->sollicitaties->contains($sollicitaty)) {
            $this->sollicitaties->removeElement($sollicitaty);
            // set the owning side to null (unless already changed)
            if ($sollicitaty->getUser() === $this) {
                $sollicitaty->setUser(null);
            }
        }

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getContactpersoon(): ?string
    {
        return $this->contactpersoon;
    }

    public function setContactpersoon(?string $contactpersoon): self
    {
        $this->contactpersoon = $contactpersoon;

        return $this;
    }

    public function getGeboortedatum(): ?\DateTimeInterface
    {
        return $this->geboortedatum;
    }

    public function setGeboortedatum(?\DateTimeInterface $geboortedatum): self
    {
        $this->geboortedatum = $geboortedatum;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
}
