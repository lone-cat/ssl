<?php

namespace App\Services;

class KeyFileManager extends LowLevelFileManager
{

    public function keyExists(): bool
    {
        return $this->fileExists();
    }

    public function saveKey(string $key): void
    {
        $this->saveFile($key);
    }

}