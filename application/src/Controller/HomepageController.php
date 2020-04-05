<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CountryPeopleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @var CountryPeopleService
     */
    private CountryPeopleService $countryPeopleService;

    public function __construct(CountryPeopleService $countryPeopleService)
    {
        $this->countryPeopleService = $countryPeopleService;
    }

    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index(): Response
    {
        $this->countryPeopleService->saveMockDataInDatabase();

        return $this->render('base.html.twig', ['piechart' => $this->countryPeopleService->getDisplayChart()]);
    }
}