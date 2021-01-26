<?php

namespace App\Services;

use App\Libs\ACMECert;
use Throwable;

class CertManager
{

    private KeyFileManager $keyFileManager;
    private CertFileManager $certFileManager;
    private ACMECert $client;

    public function __construct(KeyFileManager $keyFileManager, CertFileManager $certFileManager, ACMECert $client)
    {
        $this->keyFileManager = $keyFileManager;
        $this->certFileManager = $certFileManager;
        $this->client = $client;
    }

    public function isCertValid(): bool
    {
        if (!$this->keyFileManager->keyExists()) {
            return false;
        }

        if (!$this->certFileManager->certExists()) {
            return false;
        }

        try {
            $daysReamin = $this->client->getRemainingDays('file://' . $this->certFileManager->getFileName());
        } catch (Throwable $e) {
            return false;
        }

        if ($daysReamin < 30) {
            return false;
        }

        return true;
    }

    public function recreateCertBundle(string $domain, string $docRoot): void
    {
        $key = $this->client->generateRSAKey(4096);
        $this->keyFileManager->saveKey($key);
        $fullchain = $this->client->getCertificateChain(
            'file://' . $this->keyFileManager->getFileName(),
            [
                $domain => [
                    'challenge' => 'http-01',
                    'docroot' => $docRoot,
                ]
            ],
            static function ($opts) {
                $fn = $opts['config']['docroot'] . $opts['key'];
                @mkdir(dirname($fn), 0777, true);
                file_put_contents($fn, $opts['value']);
                return function ($opts) {
                    unlink($opts['config']['docroot'] . $opts['key']);
                };
            }
        );

        $this->certFileManager->saveCert($fullchain);
    }

}