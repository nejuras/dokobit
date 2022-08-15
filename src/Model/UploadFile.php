<?php

declare(strict_types=1);

namespace Dokobit\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFile
{
    public function __construct(
        private readonly ?UploadedFile $file,
    ) {
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

}
