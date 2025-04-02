<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\HolidayRepository;
use App\Repository\PublicHolidayRepository;
use App\Repository\RequestRepository;
use App\Tools\DateTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HolidayController extends AbstractController
{
    public function __construct(private PublicHolidayRepository $publicHolidayRepository, private RequestRepository $requestRepository)
    {
    }

    #[Route('/holiday', name: 'holiday_index')]
    public function index(): Response
    {
        $publicHolidays = $this->publicHolidayRepository->findAll();
        //$myHolidays = $this->requestRepository->getApprovedRequestsByEmployee($this->getUser());


        $formattedHolidays = array_map(static function ($holiday) {
            return [
                'date' => $holiday->getDate()?->format('Y-m-d'), // Convert DateTimeImmutable to string
                'name' => $holiday->getName(),
            ];
        }, $publicHolidays);

        $myFormattedHolidays = [];



        return $this->render('holiday/index.html.twig', [
            'controller_name' => 'HolidayController',
            'publicHolidays' => $formattedHolidays,
        ]);
    }

    #[Route('/holiday/add', name: 'add_holiday')]
    public function add(): Response
    {
        $publicHolidays = $this->publicHolidayRepository->findAll();

        $formattedHolidays = array_map(function ($holiday) {
            return [
                'date' => $holiday->getDate()?->format('Y-m-d'), // Convert DateTimeImmutable to string
                'name' => $holiday->getName(),
            ];
        }, $publicHolidays);
        return $this->render('holiday/index.html.twig', [
            'controller_name' => 'HolidayController',
            'publicHolidays' => $formattedHolidays,
        ]);
    }


}
