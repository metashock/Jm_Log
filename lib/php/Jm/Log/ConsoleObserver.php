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
 * An observer that prints log messages to the console
 */
class Jm_Log_ConsoleObserver implements Jm_Log_Observer
{

    /**
     * Called by the log dispatcher when it flushes it's buffer
     *
     * @param Jm_Log $log The log dispatcher
     *
     * @return Jm_Log_ConsoleObserver
     *
     * @throw Jm_Console_Exception if something went wrong
     * with the console output
     */
    public function update(Jm_Log $log) {
        $console = Jm_Console::singleton();

        foreach($log->messages() as $message) {
            switch($message->level()) {
                case Jm_Log_Level::ERROR :
                    $level = LOG_ERR;
                    $console->errorln($message->text(), 'red');
                    break;
                    
                case Jm_Log_Level::WARNING :
                    $level = LOG_WARNING;
                    $console->errorln($message->text(), 'yellow');
                    break;

                case Jm_Log_Level::NOTICE :
                    $level = LOG_NOTICE;
                    $console->writeln($message->text(), 'blue');
                    break;

                case Jm_Log_Level::DEBUG :
                    $level = LOG_DEBUG;
                    $console->writeln($message->text(), 'white,light');
                    break;
            }
            syslog($level, $message->text());
        }
    } 
}
