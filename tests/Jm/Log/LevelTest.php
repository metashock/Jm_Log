<?php

class Jm_Log_LevelTest extends PHPUnit_Framework_TestCase
{

    /**
     */
    public function testToString() {
        $level = new Jm_Log_Level(Jm_Log_Level::ERROR);
        $this->assertEquals('ERROR', $level .'');
        $level = new Jm_Log_Level(Jm_Log_Level::WARNING);
        $this->assertEquals('WARNING', $level .'');
        $level = new Jm_Log_Level(Jm_Log_Level::NOTICE);
        $this->assertEquals('NOTICE', $level .'');
        $level = new Jm_Log_Level(Jm_Log_Level::DEBUG);
        $this->assertEquals('DEBUG', $level .'');
    }
}

