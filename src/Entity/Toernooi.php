<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToernooiRepository")
 */
class Toernooi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Speler", inversedBy="toernoois")
     */
    private $spelers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $omschrijving;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wedstrijd", mappedBy="toernooiID")
     */
    private $wedstrijds;

    public function __construct()
    {
        $this->spelers = new ArrayCollection();
        $this->wedstrijds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Speler[]
     */
    public function getSpelers(): Collection
    {
        return $this->spelers;
    }

    public function addSpeler(Speler $speler): self
    {
        if (!$this->spelers->contains($speler)) {
            $this->spelers[] = $speler;
        }

        return $this;
    }

    public function removeSpeler(Speler $speler): self
    {
        if ($this->spelers->contains($speler)) {
            $this->spelers->removeElement($speler);
        }

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

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): self
    {
        $this->datum = $datum;

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
            $wedstrijd->setToernooiID($this);
        }

        return $this;
    }

    public function removeWedstrijd(Wedstrijd $wedstrijd): self
    {
        if ($this->wedstrijds->contains($wedstrijd)) {
            $this->wedstrijds->removeElement($wedstrijd);
            // set the owning side to null (unless already changed)
            if ($wedstrijd->getToernooiID() === $this) {
                $wedstrijd->setToernooiID(null);
            }
        }

        return $this;
    }
    public function __toString() {
	   return $this->getOmschrijving();
    }
}
