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
 * Represents a log level
 */
class Jm_Log_Level
{

    /**
     * @const integer
     */
    const ERROR = 1;

    /**
     * @const integer
     */
    const WARNING = 2;

    /**
     * @const integer
     */
    const NOTICE = 4;

    /**
     * @const integer
     */
    const DEBUG = 8;


    /**
     * Integer bit flag that represents the log level internally
     *
     * @var integer
     */
    protected $intlevel;

    
    /** 
     * Constructor
     *
     * @param integer $integer Bitfield of Jm_Log_Level  constants
     *
     * @return Jm_Log_Level
     *
     * @throws InvalidArgumentException if $integer isn't an integer
     */
    public function __construct($integer) {
        Jm_Util_Checktype::check('integer', $integer);
        $this->intlevel = $integer;
    }


    /**
     *  Returns a string representation of the common log levels
     *
     *  @return string
     */
    public function __toString() {
        $options = array();

        if(($this->intlevel & self::ERROR) === self::ERROR) {
            $options []= 'ERROR';
        }
        if(($this->intlevel & self::WARNING) === self::WARNING) {
            $options []= 'WARNING';
        }
        if(($this->intlevel & self::NOTICE) === self::NOTICE) {
            $options []= 'NOTICE';
        }
        if(($this->intlevel & self::DEBUG) === self::DEBUG) {
            $options []= 'DEBUG';
        }
        return implode(' | ', $options);
    }
}

