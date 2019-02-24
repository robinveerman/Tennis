<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WedstrijdRepository")
 */
class Wedstrijd
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Toernooi", inversedBy="wedstrijds")
     * @ORM\JoinColumn(nullable=true)
     */
    private $toernooiID = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speler")
     * @ORM\JoinColumn(nullable=false)
     */
    private $speler1ID;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Speler")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $speler2ID;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speler")
     */
    private $winnaarID;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score2;

    /**
     * @ORM\Column(type="integer")
     */
    private $ronde;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToernooiID(): ?Toernooi
    {
        return $this->toernooiID;
    }

    public function setToernooiID(?Toernooi $toernooiID): self
    {
        $this->toernooiID = $toernooiID;

        return $this;
    }

    public function getWinnaarID(): ?Speler
    {
        return $this->winnaarID;
    }

    public function setWinnaarID(?Speler $winnaarID): self
    {
        $this->winnaarID = $winnaarID;

        return $this;
    }

    public function getScore1(): ?int
    {
        return $this->score1;
    }

    public function setScore1(int $score1): self
    {
        $this->score1 = $score1;

        return $this;
    }

    public function getScore2(): ?int
    {
        return $this->score2;
    }

    public function setScore2(int $score2): self
    {
        $this->score2 = $score2;

        return $this;
    }

    public function getRonde(): ?int
    {
        return $this->ronde;
    }

    public function setRonde(int $ronde): self
    {
        $this->ronde = $ronde;

        return $this;
    }

    public function getSpeler1ID(): ?Speler
    {
        return $this->speler1ID;
    }

    public function setSpeler1ID(?Speler $speler1ID): self
    {
        $this->speler1ID = $speler1ID;

        return $this;
    }

    public function getSpeler2ID(): ?Speler
    {
        return $this->speler2ID;
    }

    public function setSpeler2ID(?Speler $speler2ID): self
    {
        $this->speler2ID = $speler2ID;

        return $this;
    }
}
