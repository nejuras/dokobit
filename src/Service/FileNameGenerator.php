<?php

declare(strict_types=1);

namespace Dokobit\Service;

class FileNameGenerator
{
    public static function generate(string $originalFileName): string
    {
        return md5($originalFileName) . '-' . \date_create()->format('YmdHis');
    }
}
