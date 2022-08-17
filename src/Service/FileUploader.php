<?php

declare(strict_types=1);

namespace Dokobit\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ZipArchive;

class FileUploader implements FileUploadInterface
{
    public const PART_ZIP_FILE_NAME = '/dokobit';

    public function __construct(private readonly string $uploadDirectory)
    {
    }

    public function upload($files): void
    {
        $unlink = [];
        $zip = new ZipArchive();
        $zip->open($this->getZipArchiveFileDirectory(), ZipArchive::CREATE);

        foreach ($files as $file) {
            /** @var UploadedFile $file */
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = FileNameGenerator::generate($originalFileName) . '.' . $file->getClientOriginalExtension();

            try {
                $file->move($this->getUploadDirectory(), $fileName);
            } catch (FileException $e) {
                throw new FileNotFoundException($e->getMessage());
            }

            $zip->addFile($this->getUploadedFileDirectory($fileName));
            $unlink[] = $this->getUploadedFileDirectory($fileName);
        }
        $zip->close();

        $this->deleteFiles($unlink);
    }

    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }

    public function getZipArchiveFileDirectory(): string
    {
        return $this->getUploadDirectory() . self::PART_ZIP_FILE_NAME . uniqid() . '.zip';
    }

    public function getUploadedFileDirectory($fileName): string
    {
        return $this->getUploadDirectory() . '/' . $fileName;
    }

    public function deleteFiles($unlink): void
    {
        foreach ($unlink as $file) {
            unlink($file);
        }
    }
}