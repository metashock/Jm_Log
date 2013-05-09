<?php
/**
 *
 */
class Jm_LogTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testLog() {
        $log = new Jm_Log();
        // just for the coverage:
        $this->assertEmpty($log->messages());

        $observer = $this->getMock('Jm_Log_Observer');
        $observer->expects($this->once())
          ->method('update')
          ->with($this->equalTo($log));

        $observer2 = $this->getMock('Jm_Log_Observer');
        $observer2->expects($this->exactly(2))
          ->method('update')
          ->with($this->equalTo($log));

        $observer3 = new Jm_Log_ArrayObserver();

        $log
          ->attach($observer)
          ->attach($observer2)
          ->attach($observer3)
          ->error('Hello %s', array('World'));

        $messages = $observer3->messages();
        $this->assertNotEmpty($messages);

        $message = $messages[0];
        $this->assertEquals('Hello World', $message->text());
        $this->assertEquals(Jm_Log_Level::ERROR, $message->level()); 
            
        $log
          ->detach($observer)
          ->error('foo bar');
    }


    /**
     * Tests if Jm_Log::add() calls $obj->__toString()
     * if $obj implements the method
     */
    public function testLogObjectToString() {
        $log = new Jm_Log();
        $observer = new Jm_Log_ArrayObserver();
        $log->attach($observer);
        // I'm using 
        $log->error(new Exception('foo'));

        $this->assertCount(1, $observer->messages());
        
        $messages = $observer->messages();
        $this->assertRegExp(
            "/^exception 'Exception' with message 'foo'.+/",
            $messages[0]->text()
        );
    }


    /**
     * Tests that Jm_Log::add() calls print_r() if message
     * is not a string and not an object which implements __toString()
     */
    public function testLogPrintr() {
        $log = new Jm_Log();
        $observer = new Jm_Log_ArrayObserver();
        $log->attach($observer);
        // I'm using 
        $log->error(array(1,2,3));
        $this->assertCount(1, $observer->messages());
        $messages = $observer->messages();

        $expected = print_r(array(1,2,3), TRUE);
        $this->assertEquals($expected, $messages[0]->text());
    }


    /**
     * @expectedException Exception
     */
    public function testMethodNotExistException() {
        $log = new Jm_Log();
        $log->foo();
    }


    /**
     * @expectedException Exception
     * @dataProvider testCallExceptionDataProvider
     */
    public function testCallException() {
        $log = new Jm_Log();
        call_user_func_array(array($log, 'error'), func_get_args());
    }


    /**
     * Data provider for the method above
     */
    public function testCallExceptionDataProvider() {
        return array (
            array(),
            array('test', 'SDFSD', 'SDF'),
            array('test', array(), 1)
        );
    }
}
