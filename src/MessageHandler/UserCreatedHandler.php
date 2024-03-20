<?php

namespace App\MessageHandler;

use App\Message\UserCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UserCreatedHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        $userData = $userCreated->getData();

        $this->logger->warning('USER CREATED' . $userData);

        ## business logic, i.e. sending internal notification or queueing some other systems
    }
}
