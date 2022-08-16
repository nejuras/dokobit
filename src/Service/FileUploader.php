<?php

declare(strict_types=1);

namespace Dokobit\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FileUploader implements FileUploadInterface
{
    public function __construct(private readonly string $uploadDirectory)
    {
    }

    public function upload($files): void
    {
        foreach ($files as $file) {
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = FileNameGenerator::generate($originalFileName) . '.' . $file->guessExtension();

            try {
                $file->move($this->getUploadDirectory(), $fileName);
            } catch (FileException $e) {
                throw new FileNotFoundException($e->getMessage());
            }
        }
    }

    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }
}