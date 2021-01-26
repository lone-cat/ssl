<?php

namespace App\Services;

class FileStorage
{

    private string $rootFolder;
    private string $fileName;

    public function __construct(string $rootFolder, string $fileName)
    {
        $this->rootFolder = $rootFolder;
        $this->fileName = $fileName;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }

    public function getFullFileName(): string
    {
        return $this->rootFolder . '/' . $this->fileName;
    }

    public function exists(): bool
    {
        return file_exists($this->getFullFileName());
    }

    public function save(string $contents): void
    {
        file_put_contents($this->getFullFileName(), $contents);
    }

}