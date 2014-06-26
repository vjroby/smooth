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

        /**
         * @readwrite
         */
        protected $_hostInfo;

        /**
         * @readwrite
         */
        protected $_securePort;

        /**
         * @readwrite
         */
        protected $_port;

        /**
         * @return mixed|string
         * @throws HttpRequest\Exception\ScriptUrl
         */
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

        /**
         * @param bool $absolute
         * @return string
         */
        public function getBaseUrl($absolute = false)
        {
            if($this->_baseUrl===null)
                $this->_baseUrl=rtrim(dirname($this->getScriptUrl()),'\\public/');
            return $absolute ? $this->getHostInfo() . $this->_baseUrl : $this->_baseUrl;
        }

        /**
         * @param string $schema
         * @return string
         */
        public function getHostInfo($schema='')
        {
            if($this->_hostInfo===null)
            {
                if($secure=$this->getIsSslConnection())
                    $http='https';
                else
                    $http='http';
                if(isset($_SERVER['HTTP_HOST']))
                    $this->_hostInfo=$http.'://'.$_SERVER['HTTP_HOST'];
                else
                {
                    $this->_hostInfo=$http.'://'.$_SERVER['SERVER_NAME'];
                    $port=$secure ? $this->getSslPort() : $this->getPort();
                    if(($port!==80 && !$secure) || ($port!==443 && $secure))
                        $this->_hostInfo.=':'.$port;
                }
            }
            if($schema!=='')
            {
                $secure=$this->getIsSslConnection();
                if($secure && $schema==='https' || !$secure && $schema==='http')
                    return $this->_hostInfo;

                $port=$schema==='https' ? $this->getSslPort() : $this->getPort();
                if($port!==80 && $schema==='http' || $port!==443 && $schema==='https')
                    $port=':'.$port;
                else
                    $port='';

                $pos=strpos($this->_hostInfo,':');
                return $schema.substr($this->_hostInfo,$pos,strcspn($this->_hostInfo,':',$pos+1)+1).$port;
            }
            else
                return $this->_hostInfo;
        }

        /**
         * @return bool
         */
        public function getIsAjaxRequest()
        {
            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
        }

        /**
         * @return bool
         */
        public function getIsSslConnection()
        {
            return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on' || $_SERVER['HTTPS']==1)
            || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']=='https';
        }

        /**
         * @return int
         */
        public function getSslPort()
        {
            if($this->_securePort===null)
                $this->_securePort=$this->getIsSslConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
            return $this->_securePort;
        }

        public function getPort()
        {
            if($this->_port===null)
                $this->_port=!$this->getIsSslConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 80;
            return $this->_port;
        }
    }
}
 