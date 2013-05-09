<?php
/**
 *
 */

class Jm_Log_SyslogObserverTest extends PHPUnit_Framework_TestCase
{

    public function testLog() {
        $log = new Jm_Log();
        $observer = new Jm_Log_SyslogObserver('phpunit');
        $log->attach($observer);

        $log->error('test');
        $log->warning('test');
        $log->notice('test');
        $log->debug('test');
        $log->detach($observer);

        unset($observer);
    }
}
