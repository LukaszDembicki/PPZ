<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\CountryPeopleChart;
use App\Repository\PeopleCountryChartRepository;
use App\Utilities\Filesystem\FilesystemManager;
use CMEN\GoogleChartsBundle\GoogleCharts\Chart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


class CountryPeopleService
{
    const PEOPLE_COUNTRY_FILENAME = 'people-country.csv';

    /**
     * @var FilesystemManager
     */
    private FilesystemManager $filesystemManager;

    /**
     * @var CountryPeopleChart
     */
    private CountryPeopleChart $countryPeopleChartModel;

    /**
     * @var PeopleCountryChartRepository
     */
    private PeopleCountryChartRepository $peopleCountryChartRepository;


    public function __construct(
        FilesystemManager $filesystem,
        CountryPeopleChart $countryPeopleChartModel,
        PeopleCountryChartRepository $peopleCountryChartRepository
    )
    {
        $this->filesystemManager = $filesystem;
        $this->countryPeopleChartModel = $countryPeopleChartModel;
        $this->peopleCountryChartRepository = $peopleCountryChartRepository;
    }

    public function saveMockDataInDatabase(): void
    {
        $peopleCountryData = $this->countryPeopleChartModel->getDataFromFile(
            $this->filesystemManager->getVarDirectory() . DIRECTORY_SEPARATOR .
            self::PEOPLE_COUNTRY_FILENAME
        );

        $this->countryPeopleChartModel->savePeopleCountryData($peopleCountryData);
    }

    public function getDisplayChart(): Chart
    {
        $peopleCountryData = $this->peopleCountryChartRepository->getPeopleCountryData();
        array_unshift($peopleCountryData, ['Country', 'Number of people']);

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($peopleCountryData);

        $pieChart->getOptions()->setTitle('Number of people per country');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $pieChart;
    }
}