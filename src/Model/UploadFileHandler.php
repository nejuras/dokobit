<?php

declare(strict_types=1);

namespace Dokobit\Model;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UploadFileHandler implements MessageHandlerInterface
{
    public function __invoke(UploadFile $command): int
    {
        return Response::HTTP_OK;
    }
}
