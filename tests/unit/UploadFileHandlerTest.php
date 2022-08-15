<?php

declare(strict_types=1);

namespace App\Tests;

use Codeception\Test\Unit;
use Dokobit\Model\UploadFile;
use Dokobit\Model\UploadFileHandler;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Response;

class UploadFileHandlerTest extends Unit
{
    use ProphecyTrait;

    private UploadFileHandler $uploadFileHandler;

    protected function setUp(): void
    {
        $this->uploadFileHandler = new UploadFileHandler();
    }

    public function testShouldGetApiResponse(): void
    {
        $command = new UploadFile(null);

        $result = $this->uploadFileHandler->__invoke($command);

        $this->assertEquals(Response::HTTP_OK, $result);

    }
}
