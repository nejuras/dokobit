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
        $data = $this->getRequestData($request);

        $this->fileUploader->upload($data['files']);

        $result = $this->handle(new UploadFile($data));

        return new Response($serializer->serialize($result, 'json'), Response::HTTP_CREATED);
    }

    public function getRequestData(Request $request): array
    {
        return [
            'files' => $request->files->get('files'),
            'ipAddress' => $request->getClientIp(),
        ];
    }
}
