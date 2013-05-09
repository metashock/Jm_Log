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
 * @package Jm_Log
 */
class Jm_Log_SyslogObserver implements Jm_Log_Observer
{
    
    /**
     * @var resource
     */
    protected $handle;


    /**
     * @var string
     */
    protected $syslogident;


    /**
     * Constructor
     *
     * @param string  $syslogident The ident used for syslog
     * @param integer $options     Syslog options
     * @param integer $facility    Syslog facility
     *
     * @return Jm_Log_SyslogObserver
     *
     * @throws InvalidArgumentException if one of the arguments doesn't match
     */
    public function __construct(
        $syslogident = 'php',
        $options = LOG_ODELAY,
        $facility = LOG_USER
    ) {

        Jm_Util_Checktype::check('string', $syslogident);
        Jm_Util_Checktype::check('integer', $options);
        Jm_Util_Checktype::check('integer', $facility);

        $this->syslogident = $syslogident;
        $ret = openlog($syslogident, $options, $facility);
        if($ret !== TRUE) {
            // After reading the source code of the `syslog()` function, I
            // realized, that `openlog()` will never return FALSE. But it will
            // return `NULL` if not called with proper number of arguments
            // or invalid argument types. As it was checked before it should 
            // not happen. But pigs can fly .... 
            // @codeCoverageIgnoreStart
            throw new Exception('Failed to connect to syslog daemon');
            // @codeCoverageIgnoreEnd
        }
    }


    /**
     *  Closes the connection to system logger
     *
     *  @return void
     */
    public function __destruct() {
        closelog();
    }


    /**
     * Called by the Jm_Log if flushes it's buffers
     *
     * @param Jm_Log $log The log dispatcher
     *
     * @return Jm_Log_ArrayObserver
     */
    public function update(Jm_Log $log) {
        foreach($log->messages() as $message) {
            switch($message->level()) {
                case Jm_Log_Level::ERROR :
                    $level = LOG_ERR;
                    break;
                    
                case Jm_Log_Level::WARNING :
                    $level = LOG_WARNING;
                    break;

                case Jm_Log_Level::NOTICE :
                    $level = LOG_NOTICE;
                    break;

                case Jm_Log_Level::DEBUG :
                    $level = LOG_DEBUG;
                    break;
            }
            syslog($level, $message->text());
        }
    }
}
