<?php

namespace App\Services;

class CertFileManager extends LowLevelFileManager
{

    public function certExists(): bool
    {
        return $this->fileExists();
    }

    public function saveCert(string $cert): void
    {
        $this->saveFile($cert);
    }

}