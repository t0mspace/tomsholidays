<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\RequestApproved;
use App\Manager\RequestManager;
use App\Repository\RequestRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RequestApprovedSubscriber implements EventSubscriberInterface
{
    public function __construct(private RequestManager $requestManager, private RequestRepository $requestRepository)
    {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function onRequestApproved(RequestApproved $event): void
    {

        $this->requestManager->approve($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'request.approved' => 'onRequestApproved',
        ];
    }
}
