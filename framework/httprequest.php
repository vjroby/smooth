<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\HttpRequest\Exception as Exception;

    class HttpRequest extends Base {

        /**
         * @readwrite
         */
        protected $_scriptUrl;

        /**
         * @readwrite
         */
        protected $_baseUrl;

        public function getScriptUrl(){

            if($this->_scriptUrl===null)
            {
                $scriptName=basename($_SERVER['SCRIPT_FILENAME']);
                if(basename($_SERVER['SCRIPT_NAME'])===$scriptName)
                    $this->_scriptUrl=$_SERVER['SCRIPT_NAME'];
                elseif(basename($_SERVER['PHP_SELF'])===$scriptName)
                    $this->_scriptUrl=$_SERVER['PHP_SELF'];
                elseif(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME'])===$scriptName)
                    $this->_scriptUrl=$_SERVER['ORIG_SCRIPT_NAME'];
                elseif(($pos=strpos($_SERVER['PHP_SELF'],'/'.$scriptName))!==false)
                    $this->_scriptUrl=substr($_SERVER['SCRIPT_NAME'],0,$pos).'/'.$scriptName;
                elseif(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT'])===0)
                    $this->_scriptUrl=str_replace('\\','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']));
                else
                    throw new Exception\ScriptUrl('Unable to determine the entry script URL.');
            }
            return $this->_scriptUrl;
        }

        public function getBaseUrl($absolute=false)
        {
            if($this->_baseUrl===null)
                $this->_baseUrl=rtrim(dirname($this->getScriptUrl()),'\\/');
            return $absolute ? $this->getHostInfo() . $this->_baseUrl : $this->_baseUrl;
        }
    }
}
 