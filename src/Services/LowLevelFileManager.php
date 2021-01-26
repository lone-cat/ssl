<?php

namespace App\Services;

abstract class LowLevelFileManager
{

    protected FileStorage $fileStorage;

    public function __construct(FileStorage $fileStorage) {
        $this->fileStorage = $fileStorage;
    }

    public function getFileStorage(): FileStorage
    {
        return $this->fileStorage;
    }

    public function getFileName(): string
    {
        return $this->fileStorage->getFullFileName();
    }

    protected function fileExists(): bool
    {
        return file_exists($this->fileStorage->getFullFileName());
    }

    protected function saveFile(string $data): void
    {
        $this->fileStorage->save($data);
    }
}