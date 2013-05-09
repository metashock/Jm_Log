<?php


class Jm_Log_MessageTest extends PHPUnit_Framework_TestCase
{

    public function testGetSet() {
        $msg = new Jm_Log_Message('Hello World', Jm_Log_Level::ERROR);
        $this->assertEquals('Hello World', $msg->text());
        $this->assertEquals(Jm_Log_Level::ERROR, $msg->level());
        $this->assertEquals('ERROR: Hello World', $msg .'');
    }
}

