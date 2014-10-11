<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\HttpRequest\Exception as Exception;

    class HttpRequest extends Base {
        const HTTP_RESPONSE_OK = 200;
        const HTTP_RESPONSE_NO_CONTENT = 204;
        const HTTP_RESPONSE_MOVED_PERMANENTLY = 301;
        const HTTP_RESPONSE_FOUND = 302;
        const HTTP_RESPONSE_BAD_REQUEST = 400;
        const HTTP_RESPONSE_UNAUTHORIZED = 401;
        const HTTP_RESPONSE_ACCOUNT_BLOCKED = 402;
        const HTTP_RESPONSE_FORBIDDEN = 403;
        const HTTP_RESPONSE_NOT_FOUND = 404;
        const HTTP_RESPONSE_INTERNAL_SERVER_ERROR = 500;
        const HTTP_RESPONSE_UNKNOWN = 000;

        /**
         * @read
         */
        private $_httpResponseStrings = array(
            self::HTTP_RESPONSE_OK => "OK",
            self::HTTP_RESPONSE_NO_CONTENT => "No Content",
            self::HTTP_RESPONSE_MOVED_PERMANENTLY => "Moved Permanently",
            self::HTTP_RESPONSE_FOUND => "Found",
            self::HTTP_RESPONSE_BAD_REQUEST => "Bad Request",
            self::HTTP_RESPONSE_UNAUTHORIZED => "Unauthorized",
            self::HTTP_RESPONSE_FORBIDDEN => "Forbidden",
            self::HTTP_RESPONSE_NOT_FOUND => "Not Found",
            self::HTTP_RESPONSE_INTERNAL_SERVER_ERROR => "Internal Server Error",
            self::HTTP_RESPONSE_UNKNOWN => "Unknown",
        );

        const METHOD_GET = 0;
        const METHOD_POST = 1;
        const METHOD_PUT = 2;
        const METHOD_DELETE = 3;
        const METHOD_OPTIONS = 4;

        const FORMAT_JSON = 0;
        const FORMAT_XML = 1;

        const STATUS_OK = 1;
        const STATUS_ERROR = 0;

        const HEADER_ACCEPT_ANY = '*/*';
        const HEADER_ACCEPT_PLAIN_TEXT = 'text/plain';
        const HEADER_ACCEPT_HTML = 'text/html';
        const HEADER_ACCEPT_JSON = 'application/json';
        const HEADER_ACCEPT_XML = 'application/xml';
        const HEADER_ACCEPT_ATOM = 'application/atom+xml';
        const HEADER_ACCEPT_ENCODING = 'Accept-Encoding';

        const HEADER_DATA_FEED_TYPE = 'Feed Data-Type';
        const HEADER_SECURITY_KEY = 'Feed Security-Key';
        const HEADER_TOKEN_KEY = 'Feed Token';
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
         * @readwrite
         */
        protected $_isApiRequest;

        /**
         * @readwrite
         */
        protected $_httpResponseCode = self::HTTP_RESPONSE_OK;



        /**
         * Returns the request method that the user has accessed.
         *
         * @return mixed
         */
        public static function getRequestMethod() {
            $header_request_method = $_SERVER['REQUEST_METHOD'];

            switch($header_request_method) {
                case 'GET':
                    return self::METHOD_GET;

                case 'POST':
                    return self::METHOD_POST;

                case 'PUT':
                    return self::METHOD_PUT;

                case 'DELETE':
                    return self::METHOD_DELETE;

                default:
                    return self::METHOD_GET;
            }
        }

        public function getHttpResponseMessage($http_response) {
            if(isset($this->_httpResponseStrings[$http_response])) return $this->_httpResponseStrings[$http_response];
            return self::HTTP_RESPONSE_UNKNOWN;
        }


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
            $dirname = dirname($this->getScriptUrl());



            if($this->_baseUrl===null){
                $dirname = explode('/',dirname(trim($this->getScriptUrl())));
                $this->_baseUrl = null;

                foreach ($dirname as $dir) {
                    if (strlen($dir) != 0 && $dir != 'public' ){
                        $this->_baseUrl = DIRECTORY_SEPARATOR.$dir;
                        break;
                    }
                }



            }

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
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest'){
                \Framework\Registry::get('httpRequest')->setIsApiRequest(true);
                return true;
            }else{
                return false;
            }
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

        public function getIsApiRequest(){
            if ($this->_isApiRequest === null){
                return false;
            }else{
                return $this->_isApiRequest;
            }
        }

        public function setIsApiRequest($value){
            $this->_isApiRequest = $value;
        }

        /**
         * @return int
         */
        public function getRequestFormat() {
            $header_accept = null;
            if(isset($_SERVER['HTTP_ACCEPT'])){
                $header_accept = $_SERVER['HTTP_ACCEPT'];
            }


            // We check the accept header and if it contains application/xml we return format xml.
            // Otherwise we return format json for any accept header.
            if($header_accept == self::HEADER_ACCEPT_XML) {
                return self::FORMAT_XML;
            }

            return self::FORMAT_JSON;
        }

        public function setResponseCode( $code){
            $this->_httpResponseCode = $code;
        }

        /**
         * creates the Http Response Code in header
         */
        public function createRequestHeader(){
            header('HTTP/1.1 '.$this->httpResponseCode.' '.$this->getHttpResponseMessage($this->httpResponseCode));
        }
    }
}
 