<?php

declare(strict_types=1);

namespace Dokobit\Model;

class UploadFile
{
    public function __construct(
        private readonly ?array $data,
    ) {
    }

    public function getData(): ?array
    {
        return $this->data;
    }

}
