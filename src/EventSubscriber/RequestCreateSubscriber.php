<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\RequestCreated;
use App\Manager\RequestManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RequestCreateSubscriber implements EventSubscriberInterface
{
    public function __construct(private RequestManager $requestManager)
    {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function onRequestCreated(RequestCreated $event): void
    {
        $requestData = $event->getData();

        $this->requestManager->generateFromData($requestData);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'request.created' => 'onRequestCreated',
        ];
    }
}
