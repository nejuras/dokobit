<?php

declare(strict_types=1);

namespace Dokobit\Model;

use Doctrine\ORM\EntityManagerInterface;
use Dokobit\Entity\UploadFileStatistics;
use Dokobit\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UploadFileHandler implements MessageHandlerInterface
{
    private FileUploader $fileUploader;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager,
    ) {
        $this->fileUploader = $fileUploader;
        $this->entityManager = $entityManager;
    }

    public function __invoke(UploadFile $command): int
    {
        $values = $command->getData();
        $this->fileUploader->upload($values['file']);

        $fileStatistics = $this->entityManager->getRepository(UploadFileStatistics::class)->findOneBy(
            [
                'ipAddress' => $values['ipAddress'],
            ]
        );

        if ($fileStatistics) {
            $fileStatistics->setUsageCountPerDay($fileStatistics->getUsageCountPerDay() + 1);
        } else {
            $newFileStatistics = (new UploadFileStatistics())
                ->setIpAddress($values['ipAddress'])
                ->setUsageCountPerDay($values['usageCountPerDay'] ?? 1);

            $this->entityManager->persist($newFileStatistics);
        }
        $this->entityManager->flush();

        return Response::HTTP_CREATED;
    }
}
