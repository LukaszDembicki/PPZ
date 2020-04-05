<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeopleCountryChartRepository")
 */
class PeopleCountryChart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfPeople;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryName;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getNumberOfPeople(): ?int
    {
        return $this->numberOfPeople;
    }

    /**
     * @param int $numberOfPeople
     * @return $this
     */
    public function setNumberOfPeople(int $numberOfPeople): self
    {
        $this->numberOfPeople = $numberOfPeople;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    /**
     * @param string $countryName
     * @return $this
     */
    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }
}
