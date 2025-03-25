<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Event\RequestCreated;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestController extends AbstractController
{
    public function __construct( private EventDispatcherInterface $eventDispatcher, private RequestRepository $requestRepository )
    {
    }

    #[Route('/request', name: 'request_list', methods: ['GET'])]
    public function index(): Response
    {
        $employee = $this->getUser();
        $requests = $this->requestRepository->getRequestsByEmployee($employee);

        return $this->render('request/index.html.twig', ['requests' => $requests]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/request/add', name: 'request_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $data = $data['data'];
            $event = new RequestCreated($data['dateStart'], $data['dateEnd'], $data['user']);
            $this->eventDispatcher->dispatch($event, RequestCreated::NAME);
        } catch (\PDOException|\JsonException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(['message' => 'Request enregistrée avec succès !']);
    }
}
