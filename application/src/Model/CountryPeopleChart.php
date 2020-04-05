<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\PeopleCountryChart;
use App\Repository\PeopleCountryChartRepository;
use UnexpectedValueException;

class CountryPeopleChart
{
    const ID = 1;
    const NUMBER_OF_PEOPLE = 1;
    const COUNTRY = 2;

    private array $peopleCountryData = [];

    /**
     * @var PeopleCountryChartRepository
     */
    private PeopleCountryChartRepository $peopleCountryChartRepository;

    /**
     * CountryPeopleChart constructor.
     * @param PeopleCountryChartRepository $peopleCountryChartRepository
     */
    public function __construct(PeopleCountryChartRepository $peopleCountryChartRepository)
    {
        $this->peopleCountryChartRepository = $peopleCountryChartRepository;
    }

    /**
     * @param string $filePath
     * @return array
     */
    public function getDataFromFile(string $filePath): array
    {
        if (($handle = fopen($filePath, "r")) === false) {
            throw new UnexpectedValueException('File is empty.');
        }

        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if (!isset($this->peopleCountryData[$data[self::COUNTRY]])) {
                $this->peopleCountryData[$data[self::COUNTRY]] = 0;
            }
            $this->peopleCountryData[$data[self::COUNTRY]] += $data[self::NUMBER_OF_PEOPLE];
        }

        fclose($handle);


        return $this->peopleCountryData;
    }

    /**
     * @param array $peopleCountryData
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function savePeopleCountryData(array $peopleCountryData): void
    {
        foreach ($peopleCountryData as $countryName => $numberOfPeople)
        {
            $peopleCountryChartEntity = $this->peopleCountryChartRepository->getRowByCountry($countryName);
            if (!$peopleCountryChartEntity) {
                $peopleCountryChartEntity = new PeopleCountryChart();
                $peopleCountryChartEntity->setCountryName($countryName);
                $peopleCountryChartEntity->setNumberOfPeople($numberOfPeople);
                $this->peopleCountryChartRepository->save($peopleCountryChartEntity);
                continue;
            }

            $peopleCountryChartEntity->setNumberOfPeople($numberOfPeople);
            $this->peopleCountryChartRepository->save($peopleCountryChartEntity);
        }
    }

    /**
     * @return array
     */
    public function getPeopleCountryData(): array
    {
        return $this->peopleCountryChartRepository->getPeopleCountryData();
    }
}