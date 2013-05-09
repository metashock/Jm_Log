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
 * Log message dispatcher
 *
 * @package Jm_Log
 */
class Jm_Log
{

    /**
     * List of observers
     *
     * @var array
     */
    public $observers;


    /**
     * Internal message buffer
     *
     * @var array
     */
    protected $messages;


    /**
     * Constructor
     *
     * @return Jm_Log
     */
    public function __construct() {
        $this->observers = array();
        $this->buffer = array();
    }


    /**
     * Add's a message to log. All observers will get notified of this.
     *
     * @param mixed   $var   A log message (can be a format string)
     * @param integer $args  Format string args
     * @param integer $level A log level
     *    
     * @return Jm_Log
     *
     * @throws InvalidArgumentException id $level isn't an integer
     */
    public function add(
        $var,
        array $args = array(),
        $level = Jm_Log_Level::DEBUG
    ) {
        Jm_Util_Checktype::check('integer', $level);
        static $sprintf;
        if(!$sprintf) {
            $sprintf = new ReflectionFunction('sprintf');
        }
        // if first argument isn't a string it will be converted to a string
        if(!is_string($var)) {
            if(is_object($var) && method_exists($var, '__toString')) {
                $var = $var .'';
            } else {
                $var = print_r($var, TRUE);
            }
        }
        if(!empty($args)) {
            array_unshift($args, $var);
            $var = $sprintf->invokeArgs($args);
        }
        $this->messages []= new Jm_Log_Message($var, $level);
        return $this->notify();
    }


    /**
     * Dynamic wrapper for notify. See PHP magic methods
     *
     * Allows to call $log->error(), $log->info() ...
     *
     * @param string $method The method name
     * @param array  $args   Method arguments
     *
     * @return Jm_Log
     *
     * @throws Exception
     */
    public function __call($method, $args) {
        static $add;
        if(!$add) {
            $add = new ReflectionMethod($this, 'add');
        }
        if(!in_array($method, array(
            'error',
            'warning',
            'notice',
            'debug'
        ), TRUE)) {
            throw new Exception(
                get_called_class() . '::' . $method . '() does not exist'
            );
        }

        if(count($args) < 1) {
            throw new Exception(
                'Bad method call. Method ' 
              .  get_called_class() . '::' . $method . '() '
              . 'expects at least 1 param. Got 0'
            );
        } else if (count($args) === 1) {
            $args []= array();
        } else if (count($args) > 2) {
            throw new Exception(
                'Bad method call. Method ' 
              .  get_called_class() . '::' . $method . '() '
              . 'expects a maximum number of 2 params. Got '
              . count($args)
            );
        }

        // arg 3 is the level
        $args [3]=  constant('Jm_Log_Level::' . strtoupper($method));
        return $add->invokeArgs($this, $args);
    }


    /**
     * Returns the message buffer
     *
     * @return Jm_Log_Message|NULL
     */
    public function messages() {
        return $this->messages;
    }


    /**
     * Attaches an observer
     *
     * @param Jm_Log_Observer $observer An observer
     *
     * @return Jm_Log
     */
    public function attach (Jm_Log_Observer $observer ) {
        $this->observers []= $observer;
        return $this;
    }


    /**
     * Detaches an observer
     *
     * @param Jm_Log_Observer $observer An observer
     *
     * @return Jm_Log
     */
    public function detach (Jm_Log_Observer $observer ) {
        $observers = array();
        foreach($this->observers as $o) {
            if($observer === $o) {
                continue;
            }
            $observers []= $o;
        }
        $this->observers = $observers;
        return $this;
    }


    /**
     * Notify all attached observers
     *
     * @return Jm_Log
     */
    public function notify () {
        foreach($this->observers as $observer) {
            $observer->update($this);
        }
        // clear message buffer
        $this->messages = array();
        return $this;
    }
}

