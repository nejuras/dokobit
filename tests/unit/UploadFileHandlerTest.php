<?php

declare(strict_types=1);

namespace App\Tests;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Dokobit\Entity\UploadFileStatistics;
use Dokobit\Model\UploadFile;
use Dokobit\Model\UploadFileHandler;
use Dokobit\Repository\UploadFileStatisticsRepository;
use PHPUnit\Util\Reflection;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use SebastianBergmann\ObjectReflector\ObjectReflector;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UploadFileHandlerTest extends Unit
{
    public const IP_ADDRESS = '172.0.0.1';

    use ProphecyTrait;

    private UploadFileHandler $uploadFileHandler;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    private UploadFileStatisticsRepository|ObjectProphecy $repo;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->repo = $this->prophesize(UploadFileStatisticsRepository::class);

        $this->uploadFileHandler = new UploadFileHandler(
            $this->repo->reveal(),
            $this->entityManager->reveal(),
        );
    }

    /**
     * @throws \ReflectionException
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

        $this->repo->findOneBy(
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
        return new UploadedFile($this->getUploadedFileUrl(), 'files', 'csv')
            ;
    }

    protected function getUploadedFileUrl(): string
    {
        return codecept_data_dir(__DIR__ . '/../_data/files.csv');
    }
}
