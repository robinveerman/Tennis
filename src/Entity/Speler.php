<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpelerRepository")
 */
class Speler
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="spelers")
     */
    private $schoolID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roepnaam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tussenvoegsel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $achternaam;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Toernooi", mappedBy="spelers")
     */
    private $toernoois;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wedstrijd", mappedBy="winnaarID")
     */
    private $wedstrijds;

    public function __construct()
    {
        $this->toernoois = new ArrayCollection();
        $this->speler2ID = new ArrayCollection();
        $this->wedstrijds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolID(): ?School
    {
        return $this->schoolID;
    }

    public function setSchoolID(?School $schoolID): self
    {
        $this->schoolID = $schoolID;

        return $this;
    }

    public function getRoepnaam(): ?string
    {
        return $this->roepnaam;
    }

    public function setRoepnaam(string $roepnaam): self
    {
        $this->roepnaam = $roepnaam;

        return $this;
    }

    public function getTussenvoegsel(): ?string
    {
        return $this->tussenvoegsel;
    }

    public function setTussenvoegsel(string $tussenvoegsel): self
    {
        $this->tussenvoegsel = $tussenvoegsel;

        return $this;
    }

    public function getAchternaam(): ?string
    {
        return $this->achternaam;
    }

    public function setAchternaam(string $achternaam): self
    {
        $this->achternaam = $achternaam;

        return $this;
    }

    /**
     * @return Collection|Toernooi[]
     */
    public function getToernoois(): Collection
    {
        return $this->toernoois;
    }

    public function addToernoois(Toernooi $toernoois): self
    {
        if (!$this->toernoois->contains($toernoois)) {
            $this->toernoois[] = $toernoois;
            $toernoois->addSpeler($this);
        }

        return $this;
    }

    public function removeToernoois(Toernooi $toernoois): self
    {
        if ($this->toernoois->contains($toernoois)) {
            $this->toernoois->removeElement($toernoois);
            $toernoois->removeSpeler($this);
        }

        return $this;
    }

    /**
     * @return Collection|Wedstrijd[]
     */
    public function getSpeler2ID(): Collection
    {
        return $this->speler2ID;
    }

    public function addSpeler2ID(Wedstrijd $speler2ID): self
    {
        if (!$this->speler2ID->contains($speler2ID)) {
            $this->speler2ID[] = $speler2ID;
            $speler2ID->setSpelerID($this);
        }

        return $this;
    }

    public function removeSpeler2ID(Wedstrijd $speler2ID): self
    {
        if ($this->speler2ID->contains($speler2ID)) {
            $this->speler2ID->removeElement($speler2ID);
            // set the owning side to null (unless already changed)
            if ($speler2ID->getSpelerID() === $this) {
                $speler2ID->setSpelerID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Wedstrijd[]
     */
    public function getWedstrijds(): Collection
    {
        return $this->wedstrijds;
    }

    public function addWedstrijd(Wedstrijd $wedstrijd): self
    {
        if (!$this->wedstrijds->contains($wedstrijd)) {
            $this->wedstrijds[] = $wedstrijd;
            $wedstrijd->setWinnaarID($this);
        }

        return $this;
    }

    public function removeWedstrijd(Wedstrijd $wedstrijd): self
    {
        if ($this->wedstrijds->contains($wedstrijd)) {
            $this->wedstrijds->removeElement($wedstrijd);
            // set the owning side to null (unless already changed)
            if ($wedstrijd->getWinnaarID() === $this) {
                $wedstrijd->setWinnaarID(null);
            }
        }

        return $this;
    }
    public function __toString() {
	   return $this->getRoepnaam() . ' ' . $this->getAchternaam();
    }
}
