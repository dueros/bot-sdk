<?php 
namespace Baidu\Duer\Botsdk;
class Log{
    //log级别 1:fatal2:notice3:debug 4:print out
    private $_level;
    private $_fp;
    private $_path;
    private $_filename;
    private $options;
    private $_wfName = 'wf.log';
    private $data = [];
    private $timeSt = [];

    const FATAL=1;
    const WARN=2;
    const NOTICE=3;
    const DEBUG=4;
    const PRINT_OUT=5;

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

        $this->logid = $_SERVER['HTTP_SAIYALOGID'] ? $_SERVER['HTTP_SAIYALOGID']:'';
        date_default_timezone_set('Asia/Shanghai');
    }

    public function markStart($key){
        if(!$key) {
            return; 
        }
        $this->timeSt[$key] = microtime(1);
    }

    public function markEnd($key){
        if(!isset($this->timeSt[$key])) {
            return; 
        }

        $start = $this->timeSt[$key];
        unset($this->timeSt[$key]);
        $this->data[$key] = intval(1000*(microtime(1) - $start));
    }

    public function getField($key){
        return isset($this->data[$key]) ? $this->data[$key]:'';
    }

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
     */
    public function setLevel($level = self::FATAL) {
        $this->_level = $level;
    }
    
    public function open($path = false) {
        $this->_filename = $this->getFileName();
        $this->_path = $path ? $path : $this->_path;
    }
    
    public function close() {
        
    }
    
    private function put($str, $filename='') {
        if(!$filename){
            $newname = $this->getFileName();
            if ($newname != $this->_filename) {
                $this->close();
                $this->open();
            }

            $filename = $this->_filename;
        }
        
        $now = date('[Y-m-d H:i:s:');
        $t = gettimeofday();
        if($this->_in_line){
            $str=str_replace(["\r","\n"],'',$str);
        }
        
        file_put_contents($this->_path.$filename, $now.$t["usec"]."] [".$this->logid."] ".$str."\n",FILE_APPEND|LOCK_EX);
        if ($this->_level == self::PRINT_OUT) {
            echo "<div style='color:red'>".$now.$t["usec"]."] ".$str."</div>\n";
        }
    }
    
    /**
     * 输出fatal日志
     * @param string $str
     * @param string $logType ui/trace
     */
    public function fatal($str) {
        if ($this->_level >= self::FATAL) {
            //$this->put("[FATAL] $str".$this->backtrace(), $this->_wfName);
            $this->put("[FATAL] [$str] ".$this->caller(), $this->_wfName);
        }
    }
    
    /**
     * 输入notice日志
     * @param string $str
     * @param string $logType ui/trace
     */
    public function notice($str='') {
        if ($this->_level >= self::NOTICE) {
            $arr = [];
            foreach($this->data as $k=>$v){
                $arr[] = "$k:$v"; 
            }
            $this->put("[NOTICE] [$str] ".implode(' ', $arr));
        }
    }
    
    /**
     * 输出warn日志
     * @param string $str
     * @param string $logType ui/trace
     */
    public function warn($str, $logType = "") {
        if ($this->_level >= self::WARN) {
            $this->put("[WARN] [$str] ".$this->caller(), $this->_wfName);
        }
    }
    
    public function debug($str) {
        if ($this->_level >= self::DEBUG) {
            $this->put("[DEBUG] [$str] ".$this->caller());
        }
    }
    
    private function getFileName() {
        if($this->_file_prefix && !$this->options['time_split']){
            return $this->_file_prefix.".log";
        }
        return $this->_file_prefix.date('YmdH').".log";
    }
    
    
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
