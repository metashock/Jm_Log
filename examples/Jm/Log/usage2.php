<?php

require_once 'Jm/Autoloader.php';
// nessary to make it work even if the package hasn't been installed with PEAR
Jm_Autoloader::singleton()->prependPath(
    dirname(__FILE__) . '/../../../lib/php/'
);

$log = new Jm_Log();

$log->attach(new Jm_Log_FileObserver('test.log'));
$log->attach(new Jm_Log_SyslogObserver('phpunit'));
$log->attach(new Jm_Log_ConsoleObserver());

while (TRUE) {
    $log->notice(rand());
}
