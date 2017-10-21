<?php 
/**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @desc 日志类
 **/
namespace Baidu\Duer\Botsdk;
class Log{
    //log级别 1:fatal2:notice3:debug 4:print out
    private $_level;
    private $_path;
    private $options;
    private $_wfName = 'wf.log';
    private $data = [];
    private $timeSt = [];

    const FATAL=1;
    const WARN=2;
    const NOTICE=3;
    const DEBUG=4;
    const PRINT_OUT=5;

    /**
     * @param array $options
     * @return null
     **/
    public function __construct($options=[]){
        $options=array_merge([
            'level'=>self::FATAL,
            'path'=>'/tmp/',
            'file_prefix'=>'',
            'in_line'=>true,///每条日志仅打一行
            'time_split'=>true,
            ],$options);
        $this->_file_prefix=$options['file_prefix'];
        $this->_level=$options['level'];
        $this->_path=$options['path'];
        $this->_in_line=$options['in_line'];
        $this->options=$options;

        $this->logid = $_SERVER['HTTP_SAIYALOGID'] ? $_SERVER['HTTP_SAIYALOGID']:''.time().mt_rand(1000, 10000);
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * @desc 时间记录，计时开始
     * @param string $key
     * @return null
     **/
    public function markStart($key){
        if(!$key) {
            return; 
        }
        $this->timeSt[$key] = microtime(1);
    }

    /**
     * @desc 时间记录，计时结束
     * @param string $key
     * @return null
     **/
    public function markEnd($key){
        if(!isset($this->timeSt[$key])) {
            return; 
        }

        $start = $this->timeSt[$key];
        unset($this->timeSt[$key]);
        $this->data[$key] = intval(1000*(microtime(1) - $start));
    }

    /**
     * @desc 获取log记录的某个字段
     * @param string $key
     * @return string
     **/
    public function getField($key){
        return isset($this->data[$key]) ? $this->data[$key]:'';
    }

    /**
     * @desc 设置log记录一个字段
     * @param string $key
     * @return null
     **/
    public function setField($key, $value){
        if(!$key) {
            return; 
        }

        $this->data[$key] = $value;
    }

    /**
     * 设置log级别
     *
     * @param num $level
     * @return null
     */
    public function setLevel($level = self::FATAL) {
        $this->_level = $level;
    }
    
    /**
     * @param string $path
     * @return null
     **/
    private function open($path = false) {
    }
    
    /**
     * @param string $str
     * @param string $filename
     * @return null
     **/
    private function put($str, $level) {
        $filename = $this->getFileName($level);

        $map = ["FATAL", "WARN", "NOTICE", "DEBUG", "PRINT_OUT"];
        $levelName = $map[$level - 1];
        $now = date('[Y-m-d H:i:s:');
        $t = gettimeofday();
        if($this->_in_line){
            $str=str_replace(["\r","\n"],'',$str);
        }
        
        file_put_contents($this->_path.$filename,"[$levelName] ". $now.$t["usec"]."] [".$this->logid."] ".$str."\n",FILE_APPEND|LOCK_EX);
        if ($this->_level == self::PRINT_OUT) {
            echo "<div style='color:red'>".$now.$t["usec"]."] ".$str."</div>\n";
        }
    }
    
    /**
     * 输出fatal日志
     * @param string $str
     * @return null
     */
    public function fatal($str) {
        if ($this->_level >= self::FATAL) {
            //$this->put("[FATAL] $str".$this->backtrace(), $this->_wfName);
            //$this->put("[FATAL] [$str] ".$this->caller(), $this->_wfName);
            $this->put("$str ".$this->caller(), self::FATAL);
        }
    }
    
    /**
     * 输入notice日志
     * @param string $str
     * @return null
     */
    public function notice($str='') {
        if ($this->_level >= self::NOTICE) {
            $arr = [];
            foreach($this->data as $k=>$v){
                $arr[] = str_replace(" ", "%20", "$k:$v"); 
            }
            //$this->put("[NOTICE] [$str] ".implode(' ', $arr));
            $this->put("[$str] ".implode(' ', $arr), self::NOTICE);
        }
    }
    
    /**
     * 输出warn日志
     * @param string $str
     * @return null
     */
    public function warn($str, $logType = "") {
        if ($this->_level >= self::WARN) {
            //$this->put("[WARN] [$str] ".$this->caller(), $this->_wfName);
            $this->put("$str ".$this->caller(), self::WARN);
        }
    }
    
    /**
     * 输出debug 日志
     * @param string $str
     * @return null
     */
    public function debug($str) {
        if ($this->_level >= self::DEBUG) {
            $this->put("$str ".$this->caller(), self::DEBUG);
        }
    }
    
    /**
     * @param null
     * @return null
     **/
    private function getFileName($level) {
        $part = $level >= self::NOTICE ? "" : ".wf";
        $prefix = $this->_file_prefix ? $this->_file_prefix.".log$part" : "log$part";
        if($this->_file_prefix && !$this->options['time_split']){
            return $prefix;
        }
        return "$prefix.".date('YmdH');
    }
    
    /**
     * @param null
     * @return null
     **/
    private function caller() {
        $bt = debug_backtrace();
        array_shift($bt);
        array_shift($bt);
        $data = '';
        $point = array_shift($bt);
        $func = isset($point['function']) ? $point['function'] : '';
        $file = isset($point['file']) ? substr($point['file'], strlen($basePath)) : '';
        $line = isset($point['line']) ? $point['line'] : '';
        $args = isset($point['args']) ? $point['args'] : '';
        $class = isset($point['class']) ? $point['class'] : '';
        if ($class) {
            $data .= "# ${class}->${func} at [$file:$line]";
        } else {
            $data .= "# $func at [$file:$line]";
        }
        
        return $data;
    }
    
    /**
     * @param null
     * @return null
     **/
    private function backtrace($basePath = "") {
        $bt = debug_backtrace();
        array_shift($bt);
        $data = '';
        foreach ($bt as $i=>$point) {
            $func = isset($point['function']) ? $point['function'] : '';
            $file = isset($point['file']) ? substr($point['file'], strlen($basePath)) : '';
            $line = isset($point['line']) ? $point['line'] : '';
            $args = isset($point['args']) ? $point['args'] : '';
            $class = isset($point['class']) ? $point['class'] : '';
            if ($class) {
                $data .= "#$i ${class}->${func} at [$file:$line]\t";
            } else {
                $data .= "#$i $func at [$file:$line]\t";
            }
        }
        
        return $data;
    }
}
