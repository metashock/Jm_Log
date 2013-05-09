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
 * Represents a log message
 */
class Jm_Log_Message
{

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var integer
     */
    protected $level;


    /** 
     * Constructor
     *
     * @param string   $text  The message text
     * @param integer  $level The message level
     * @param DateTime $time  The message time
     *
     * @throws InvalidArgumentException if one of the argument's type
     * don't matches
     *
     * @return Jm_Log
     */
    public function __construct(
        $text,
        $level = Jm_Log_Level::DEBUG,
        DateTime $time = NULL
    ) {
        Jm_Util_Checktype::check('string', $text);
        Jm_Util_Checktype::check('integer', $level);
        $this->text($text);
        $this->level($level);
        if(is_null($time)) {
            $time = new DateTime();
        }
        $this->time($time);
    }


    /**
     * Getter or setter for the text property
     *
     * @param string|NULL $value The message
     *
     * @return string|Jm_Log_Message
     *
     * @throws InvalidArgumentException if $value is not a string or NULL
     */
    public function text($value = NULL) {
        if(is_null($value)) {
            return $this->text;
        } else {
            Jm_Util_Checktype::check('string', $value);
            $this->text = $value;
            return $this;
        }
    }


    /**
     * Getter or setter for the level property
     *
     * @param string|NULL $value The level
     *
     * @return integer|Jm_Log_Message
     *
     * @throws InvalidArgumentException if $value is not an integer or NULL
     */
    public function level($value = NULL) {
        if(is_null($value)) {
            return $this->level;
        } else {
            Jm_Util_Checktype::check('integer', $value);
            $this->level = $value;
            return $this;
        }
    }


    /**
     * Getter or setter for the level property
     *
     * @param DateTime|NULL $value The time
     *
     * @return DateTime|Jm_Log_Message
     *
     * @throws InvalidArgumentException if $value is not an integer or NULL
     */
    public function time(DateTime $value = NULL) {
        if(is_null($value)) {
            return $this->time;
        } else {
            $this->time = $value;
            return $this;
        }
    }


    /**
     * Magic method. Returns a string representation of the message
     *
     * @return string
     */
    public function __toString() {
        $level = new Jm_Log_Level($this->level());
        return $level . ': ' . $this->text();
    }
}
