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
 * An observer that stores log messages to a file
 */
class Jm_Log_FileObserver implements Jm_Log_Observer
{

    /**
     * @var string
     */
    protected $filename;


    /**
     * @var resource
     */
    protected $fd;


    /**
     * Constructor
     *
     * @param string  $filename   The filename of the logfile
     * @param integer $buffersize Size of the buffer in number of messages
     *
     * @return Jm_Log_FileObserver
     *
     * @throws InvalidArgumentException if the data types
     * of the arguments don't match
     */
    public function __construct($filename, $buffersize = 0) {
        Jm_Util_Checktype::check('string', $filename);
        Jm_Util_Checktype::check('integer', $buffersize);
        $this->filename = $filename; 
    }


    /**
     * Makes sure that the log file will get closed
     *
     * @return void
     */
    public function __destruct() {
        if(is_resource($this->fd)) {
            fclose($this->fd);
        }
    }


    /**
     * Called by the log dispatcher if it flushes it's buffers
     *
     * @param Jm_Log $log The log dispatcher
     *
     * @return Jm_Log
     */
    public function update(Jm_Log $log) {
        if(!is_resource($this->fd)) {
            $this->fd = @fopen($this->filename, 'w+');
            if(!is_resource($this->fd)) {
                throw new Jm_Filesystem_FileNotWritableException (
                    'Failed to open \'' . $this->filename . '\' for writing'
                );
            }
        }
        foreach($log->messages() as $message) {
            $datetime = $message->time()->format('[Y/m/d H:i:s] ');
            fwrite($this->fd, $datetime . $message->text() . PHP_EOL);
        }
        fflush($this->fd);
    }
}   
