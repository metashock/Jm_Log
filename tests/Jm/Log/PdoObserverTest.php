<?php

class Jm_Log_PdoObserverTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testLog() {
        $log = new Jm_Log();
        $observer = new Jm_Log_PdoObserver('sqlite::memory:');
        $this->createTable($observer->pdo());
        $observer->querystring($this->querystring());
        $this->assertEquals(
            $this->querystring(),
            $observer->querystring()
        );

        $log->attach($observer);
        $log->error('the error');

        $messages = $observer
          ->pdo()
          ->query('SELECT * FROM `log_message`')
          ->fetchAll();

        $this->assertCount(1, $messages);
        $this->assertEquals('the error', $messages[0]['text']); 
        $this->assertEquals(
            Jm_Log_Level::ERROR,
            intval($messages[0]['level'])
        );
    }


    /**
     * Currently just for code coverage
     */
    public function testExternalPdo() {
        $log = new Jm_Log();
        $pdo = new PDO('sqlite::memory:');
        $observer = new Jm_Log_PdoObserver($pdo);
        $this->createTable($pdo);
        $observer->querystring($this->querystring());
        $this->assertEquals(
            $this->querystring(),
            $observer->querystring()
        );

        $log->attach($observer);
        $log->error('the error');

        $messages = $pdo
          ->query('SELECT * FROM `log_message`')
          ->fetchAll();

        $this->assertCount(1, $messages);
        $this->assertEquals('the error', $messages[0]['text']); 
        $this->assertEquals(
            Jm_Log_Level::ERROR,
            intval($messages[0]['level'])
        );
    }


    /**
     * Helper function. Creates the log table
     */
    protected function createTable($pdo) {
        return $pdo->query('
        CREATE TABLE `log_message` (
          `id` BIGINT auto_increment,
          `text` TEXT(4096),
          `level` SMALLINT,
          `time` DATETIME,
          PRIMARY KEY(`id`)
        )');
    }


    /**
     * @return string
     */
    protected function querystring() {
        return '
        INSERT INTO `log_message` (
          `text`, `level`, `time`
        ) VALUES (
          :text, :level, :time
        )';
    }
}
