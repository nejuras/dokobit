<?php

declare(strict_types=1);

namespace Dokobit\Model;

use Doctrine\ORM\EntityManagerInterface;
use Dokobit\Entity\UploadFileStatistics;
use Dokobit\Repository\UploadFileStatisticsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UploadFileHandler implements MessageHandlerInterface
{
    private UploadFileStatisticsRepository $uploadFileStatisticsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UploadFileStatisticsRepository $uploadFileStatisticsRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->uploadFileStatisticsRepository = $uploadFileStatisticsRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(UploadFile $command): UploadFileStatistics
    {
        $values = $command->getData();

        $fileStatistics = $this->uploadFileStatisticsRepository->findOneBy(
            [
                'ipAddress' => $values['ipAddress'],
            ]
        );

        if ($fileStatistics) {
            $fileStatistics->setUsageCountPerDay($fileStatistics->getUsageCountPerDay() + 1);
        } else {
            $fileStatistics = (new UploadFileStatistics())
                ->setIpAddress($values['ipAddress'])
                ->setUsageCountPerDay($values['usageCountPerDay'] ?? 1);

            $this->entityManager->persist($fileStatistics);
        }
        $this->entityManager->flush();

        return $fileStatistics;
    }
}
