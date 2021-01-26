<?php

namespace App\Services;

use App\Libs\ACMECert;

class AccountManager
{

    private KeyFileManager $keyManager;
    private ACMECert $client;

    public function __construct(KeyFileManager $keyManager, ACMECert $client) {
        $this->keyManager = $keyManager;
        $this->client = $client;
    }

    public function getKeyManager(): KeyFileManager
    {
        return $this->keyManager;
    }

    public function getClient(): ACMECert
    {
        return $this->client;
    }

    public function accountExists(): bool
    {
        return $this->keyManager->keyExists();
    }

    public function loadAccount(): void
    {
        $this->client->loadAccountKey('file://' . $this->keyManager->getFileName());
    }

    public function registerAccount(string $email): void
    {
        $key = $this->client->generateRSAKey(4096);
        $this->keyManager->saveKey($key);
        $this->loadAccount();
        $this->client->register(true, $email);
    }

}