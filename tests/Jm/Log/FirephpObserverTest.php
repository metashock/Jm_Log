<?php

class Jm_Log_FirephpObserverTest extends PHPUnit_Framework_TestCase
{

    public function testLog() {
        $log = new Jm_Log();
        $observer = new Jm_Log_FirephpObserver();
        $log->attach($observer);
        $log->notice('this is a notice');
    }
}

