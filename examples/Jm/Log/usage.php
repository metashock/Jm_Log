<?php

require_once 'Jm/Autoloader.php';
// nessary to make it work even if the package hasn't been installed with PEAR
Jm_Autoloader::singleton()->prependPath(
    dirname(__FILE__) . '/../../../lib/php/'
);

$log = new Jm_Log();
# will print messages to the console
$log->attach(new Jm_Log_ConsoleObserver());
# dispatches messages to syslog. check syslogs to see
$log->attach(new Jm_Log_SyslogObserver(basename(__FILE__)));
# will append logs to the file test.log
$log->attach(new Jm_Log_FileObserver('test.log'));

# currently 4 levels of messages are available
$log->notice('this is a notice');
$log->debug('this is a debug message');
$log->warning('this is a warning');
$log->error('this is an error');

