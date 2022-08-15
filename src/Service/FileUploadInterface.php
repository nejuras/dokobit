<?php

declare(strict_types=1);

namespace Dokobit\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadInterface
{
    public function upload(UploadedFile $file): void;
}
