<?php

namespace App\Controller;

use App\DTO\HolidayRequestDTO;
use App\Entity\Employee;
use App\Event\RequestApproved;
use App\Event\RequestCreated;
use App\Exceptions\DatesOverlapingException;
use App\Repository\EmployeeRepository;
use App\Repository\RequestRepository;
use App\Enum\RequestStatus;
use App\Validator\RequestDatesOverlaping;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Validator\RequestDatesOverlapingValidator;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class RequestController extends AbstractController
{
    private $employeeRepository;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private RequestRepository $requestRepository,
        private RequestDatesOverlapingValidator $requestDatesOverlapingValidator,
        private ValidatorInterface $validator
    )
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
     * @throws \Exception
     */
    #[Route('/request/add', name: 'request_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $dto = new HolidayRequestDTO($data);

            // Step 1: Validate DTO
            $violations = $this->validator->validate(
                $dto,
                new RequestDatesOverlaping)
            ;

            if (count($violations) > 0) {
                throw new DatesOverlapingException('Validation failed: ' . (string) $violations);
            }

            $eventCreated = new RequestCreated($dto);

            $this->eventDispatcher->dispatch($eventCreated, RequestCreated::NAME);
        } catch (\PDOException|\JsonException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (DatesOverlapingException $e) {
            return new JsonResponse('Dates choisies déjà prises', Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse(['message' => 'Request enregistrée avec succès !']);
    }

    #[Route('/request/all', name: 'all_other_requests', methods: ['GET']), IsGranted("ROLE_MANAGER")]
    public function getAll(): Response
    {
        $employee = $this->getUser();
        $requests = $this->requestRepository->getAllOtherRequests($employee);

        return $this->render('request/all.html.twig', ['requests' => $requests]);
    }

    #[Route('/request/approve', name: 'app_request_approve', methods: ['GET']), IsGranted("ROLE_MANAGER")]
    public function approve(Request $request, EmployeeRepository $employeeRepository): JsonResponse
    {
        try{
            $manager = $employeeRepository->findByEmail($this->getUser()?->getEmail());
            $event = new RequestApproved($request->get('id'), RequestStatus::APPROVED,$manager);
            $this->eventDispatcher->dispatch($event, RequestApproved::NAME);
        }catch (PDOException $e){
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }catch (\Throwable $e){
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Demande approuvée !']);
    }


}
