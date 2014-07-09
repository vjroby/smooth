<?php

class Logger
{
    const TYPE_INFO = 0;
    const TYPE_ERROR = 1;

    protected $_dir;
    protected $_file;
    protected $_type;
    protected $_entries;
    protected $_start;
    protected $_end;
    protected $_log_permissions = "0493";
    protected $_dir_errors = "errors";
    protected $_info = "info";

    protected function _sum($values)
    {
        $count = 0;

        foreach ($values as $value)
        {
            $count += $value;
        }

        return $count;
    }

    protected function _average($values)
    {
        return $this->_sum($values) / sizeof($values);
    }

    public function __construct($options)
    {
        if (!isset($options["file"]))
        {
            throw new Exception("Log file invalid.");
        }
        if (!isset($options["dir"]))
        {
            throw new Exception("Log dir invalid.");
        }
        if (!isset($options["type"]))
        {
            throw new Exception("Log dir invalid.");
        }
        $this->_dir = $options["dir"];
        $this->_file = $options["file"];
        $this->_type = $options["type"];
        $this->_entries = array();
        $this->_start = microtime();
    }

    public function log($message)
    {
        $this->_entries[] = array(
            "message" => "[" . date("Y-m-d H:i:s") . "]" . $message,
            "time" => microtime()
        );
    }

    public function __destruct()
    {
        $messages = "";
        $path = "";
        $last = $this->_start;
        $times = array();

        foreach ($this->_entries as $entry)
        {
            $messages .= $entry["message"] . "\n";
            $times[] = $entry["time"] - $last;
            $last = $entry["time"];
        }
        if ($this->_type === self::TYPE_INFO){
            $messages .= "Average: " . $this->_average($times);
            $messages .= ", Longest: " . max($times);
            $messages .= ", Shortest: " . min($times);
            $messages .= ", Total: " . (microtime() - $this->_start);
            $messages .= "\n";
        }


        $year = date('Y');
        $month =date('M');

        $this->createDir($this->_dir);
        $path .= $this->_dir.DIRECTORY_SEPARATOR;

        switch ($this->_type){

            case self::TYPE_INFO:
                $this->createDir($path.$this->_info);
                $path .= $this->_info.DIRECTORY_SEPARATOR;
                break;

            case self::TYPE_ERROR:
                $this->createDir($path.$this->_dir_errors);
                $path .= $this->_dir_errors.DIRECTORY_SEPARATOR;
                break;
        }

        $this->createDir($path.$year);
        $path .= $year.DIRECTORY_SEPARATOR;
        $this->createDir($path.$month);
        $path .= $month.DIRECTORY_SEPARATOR.$this->_file;

        file_put_contents($path, $messages, FILE_APPEND);
    }

    protected function createDir($dir){
        if(!is_dir($dir)) mkdir($dir, $this->_log_permissions);
    }

    public static function getLoggerForErrors($options){
        return new Logger($options);
    }
}