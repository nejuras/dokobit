<?php

declare(strict_types=1);

namespace Dokobit\Tests;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Dokobit\Model\UploadFile;
use Dokobit\Model\UploadFileHandler;
use Dokobit\Service\FileUploader;
use Dokobit\Service\FileUploadInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UploadFileHandlerTest extends Unit
{
    use ProphecyTrait;

    private UploadFileHandler $uploadFileHandler;

    private FileUploadInterface|ObjectProphecy $fileUpload;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->fileUpload = new FileUploader($this->getUploadedFileUrl());
        $this->uploadFileHandler = new UploadFileHandler(
            $this->fileUpload,
            $this->entityManager->reveal(),
        );
    }

    public function testShouldGetApiResponse(): void
    {
        $command = new UploadFile(['file' => $this->getUploadedFile()]);

        $this->expectException(FileNotFoundException::class);

        $result = $this->uploadFileHandler->__invoke($command);

        $this->assertEquals(Response::HTTP_OK, $result);

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
