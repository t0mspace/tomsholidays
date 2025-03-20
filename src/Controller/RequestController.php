<?php

namespace App\Controller;

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
    public function __construct( private EventDispatcherInterface $eventDispatcher)
    {
    }

    #[Route('/request', name: 'app_request')]
    public function index(): Response
    {
        return $this->render('request/index.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/request/add', name: 'request_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $data = $data['data'];
        $event = new RequestCreated($data['dateStart'], $data['dateEnd'], $data['user']);
        $this->eventDispatcher->dispatch($event, RequestCreated::NAME);
    }
}
