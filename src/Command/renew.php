<?php

namespace App\Command;

use App\Libs\ACMECert;
use App\Services\AccountManager;
use App\Services\CertFileManager;
use App\Services\CertManager;
use App\Services\FileStorage;
use App\Services\KeyFileManager;

require('/app/src/bootstrap.php');

$ret = `apache2-foreground > /dev/null &`;

foreach ($domains as $domain) {

    if (!$domain) {
        break;
    }

    $accountKeyFileName = $domain . '.account.key';
    $serverKeyFileName = $domain . '.key';
    $serverCertFileName = $domain . '.crt';

    $accountKeyStorage = new FileStorage($sslFolder, $accountKeyFileName);
    $accountKeyManager = new KeyFileManager($accountKeyStorage);

    $client = new ACMECert();

    $accountManager = new AccountManager($accountKeyManager, $client);

    if ($accountManager->accountExists()) {
        $accountManager->loadAccount();
    } else {
        $accountManager->registerAccount($email);
    }

    $serverKeyStorage = new FileStorage($sslFolder, $serverKeyFileName);
    $serverKeyManager = new KeyFileManager($serverKeyStorage);

    $severCertStorage = new FileStorage($sslFolder, $serverCertFileName);
    $serverCertManager = new CertFileManager($severCertStorage);

    $certManager = new CertManager($serverKeyManager, $serverCertManager, $client);

    if (!$certManager->isCertValid()) {
        $certManager->recreateCertBundle($domain, $docroot);
    }
}

echo 'ok';