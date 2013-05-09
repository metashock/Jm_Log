<?php
/**
 * Jm_Log
 *
 * Copyright (c) 2013, Thorsten Heymann <thorsten@metashock.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name Thorsten Heymann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP Version >= 5.3.0
 *
 * @category  Logging
 * @package   Jm_Log
 * @author    Thorsten Heymann <thorsten@metashock.de>
 * @copyright 2013 Thorsten Heymann <thorsten@metashock.de>
 * @license   BSD-3 http://www.opensource.org/licenses/BSD-3-Clause
 * @version   GIT: $$GITVERSION$$
 * @link      http://www.metashock.de/
 * @since     0.1.0
 */
/**
 * Log observer that saves log messages to a database using PDO
 */
class Jm_Log_PdoObserver implements Jm_Log_Observer
{

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var PDOStatement
     */
    protected $stmt;


    /**
     * Constructor
     *
     * @param string|PDO $arg1       Can be a PDO object or a connection string
     * @param string     $user       A user name
     * @param string     $pass       A password
     * @param array      $pdoOptions Array with attributes for PDO
     *
     * @return Jm_Log_PdoObserver
     *
     * @throws PDOException
     */
    public function __construct(
        $arg1,
        $user = '',
        $pass = '',
        $pdoOptions = array()
    ) {
        if(is_a($arg1, 'PDO')) {
            $this->pdo = $arg1;
        } else {
            // enforce PDO::ATTR_ERRMODE = PDO::ERRMODE_EXCEPTION
            $pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

            $this->pdo = new PDO($arg1, $user, $pass, $pdoOptions);
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
            );
        }
    }


    /**
     * Gets or sets the query string
     *
     * @param string|NULL $value The query string
     *
     * @return string|Jm_Log_PdoObserver
     *
     * @throws PDOException
     * @throws InvalidArgumentException if $value is not NULL and not a string
     */
    public function querystring($value = NULL) {
        if(is_null($value)) {
            return $this->querystring; 
        } else {
            Jm_Util_Checktype::check('string', $value);
            $this->querystring = $value;
            // prepare a statement
            $this->stmt = $this->pdo->prepare($value);
            return $this;
        }
    }


    /**
     * Called by the log dispatcher if it flushes it's buffers
     *
     * @param Jm_Log $log The log dispatcher
     *
     * @return Jm_Log_PdoObserver
     */
    public function update(Jm_Log $log) {
        foreach($log->messages() as $message) {
            $result = $this->stmt->execute (array(
                'text' => $message->text(),
                'level' => $message->level(),
                'time' => $message->time()->format(DateTime::ISO8601)
            ));
        }
        return $this;
    } 

    
    /**
     * Gets the pdo connection object
     *
     * @return PDO
     */
    public function pdo() {
        return $this->pdo;
    }
}

