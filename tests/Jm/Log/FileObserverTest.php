<?php

class Jm_Log_FileObserverTest extends PHPUnit_Framework_TestCase
{
   
    /**
     */ 
    public function test () {
        $log = new Jm_Log();

        $filename = tempnam(sys_get_temp_dir(), 'phpunit');
        $log->attach(new Jm_Log_FileObserver($filename));

        $log->error('Hello World');
        $log->error('foo bar');

        $tests = array('Hello World', 'foo bar');
        foreach(file($filename) as $line) {
            $this->assertEquals (
                1,
                preg_match('/^\[.*?\] (.*)/', $line, $matches)
            );

            $this->assertEquals(
                array_shift($tests),
                $matches[1]
            );
        }
    }


    /**
     * @expectedException Jm_Filesystem_FileNotWritableException
     */
    public function testFileNotWritableException() {
        $log = new Jm_Log();

        $filename = tempnam(sys_get_temp_dir(), 'phpunit');
        chmod($filename, 0100);
 
        $log->attach(new Jm_Log_FileObserver($filename));

        $log->error('Hello World');
    }
}
