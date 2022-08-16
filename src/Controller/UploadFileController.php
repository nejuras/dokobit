<?php

declare(strict_types=1);

namespace Dokobit\Controller;

use Dokobit\Model\UploadFile;
use Dokobit\Service\FileUploader;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadFileController extends AbstractController
{
    use HandleTrait;

    private FileUploader $fileUploader;

    public function __construct(
        MessageBusInterface $messageBus,
        FileUploader $fileUploader,
    )
    {
        $this->messageBus = $messageBus;
        $this->fileUploader = $fileUploader;
    }

    public function upload(SerializerInterface $serializer, Request $request): Response
    {
        $data = [
            'file' => $request->files->get('file'),
            'ipAddress' => $request->getClientIp(),
        ];

        $command = new UploadFile($data);

        $this->fileUploader->upload($data['file']);

        $result = $this->handle($command);

        return new Response($serializer->serialize($result, 'json'), Response::HTTP_CREATED);
    }
}
