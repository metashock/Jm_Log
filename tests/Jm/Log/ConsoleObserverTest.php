<?php

class Jm_Log_ConsoleObserverTest extends PHPUnit_Framework_TestCase
{

    public function testLog() {
        $log = new Jm_Log();
        $observer = new Jm_Log_ConsoleObserver();

        $log->attach($observer);
        $log->error('an error');
        $log->warning('a warning');
        $log->notice('a notice');
        $log->debug('a debug message');
    }

}

