<?php

declare(strict_types=1);

namespace App\Tests;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Dokobit\Entity\UploadFileStatistics;
use Dokobit\Model\UploadFile;
use Dokobit\Model\UploadFileHandler;
use Dokobit\Repository\UploadFileStatisticsRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileHandlerTest extends Unit
{
    public const IP_ADDRESS = '172.0.0.1';

    use ProphecyTrait;

    private UploadFileHandler $uploadFileHandler;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    private UploadFileStatisticsRepository|ObjectProphecy $uploadFileStatisticsRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->uploadFileStatisticsRepository = $this->prophesize(UploadFileStatisticsRepository::class);

        $this->uploadFileHandler = new UploadFileHandler(
            $this->uploadFileStatisticsRepository->reveal(),
            $this->entityManager->reveal(),
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetApiResponse(): void
    {
        $command = new UploadFile([
            'file' => $this->getUploadedFile(),
            'ipAddress' => self::IP_ADDRESS,
        ]);

        $uploadFileStatistics = (new UploadFileStatistics())
            ->setIpAddress(self::IP_ADDRESS)
            ->setUsageCountPerDay(5);

        (new \ReflectionClass(UploadFileStatistics::class))
            ->getProperty('id')
            ->setValue($uploadFileStatistics,2);

        $this->uploadFileStatisticsRepository->findOneBy(
            [
                'ipAddress' => self::IP_ADDRESS,
            ]
        )->shouldBeCalled()->willReturn($uploadFileStatistics);


        $result = $this->uploadFileHandler->__invoke($command);

        $this->assertEquals(6, $result->getUsageCountPerDay());
        $this->assertEquals(self::IP_ADDRESS, $result->getIpAddress());
        $this->assertEquals(2, $result->getId());

    }

    protected function getUploadedFile(): UploadedFile
    {
        return new UploadedFile($this->getUploadedFileUrl(), 'files', 'csv');
    }

    protected function getUploadedFileUrl(): string
    {
        return codecept_data_dir(__DIR__ . '/../_data/files.csv');
    }
}
