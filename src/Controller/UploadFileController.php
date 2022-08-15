<?php

declare(strict_types=1);

namespace Dokobit\Controller;

use Dokobit\Model\UploadFile;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadFileController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
    )
    {
        $this->messageBus = $messageBus;
    }

    public function upload(SerializerInterface $serializer, Request $request): Response
    {
        $command = new UploadFile(null, $request->request->get('text'), $request->request->get('pulp'));
        $r = $this->handle($command);

        return new Response($serializer->serialize($r, 'json'), Response::HTTP_CREATED);
    }
}
