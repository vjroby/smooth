<?php

namespace Framework
{
    class RequestMethods {

        protected $_never_allowed_str = array(
            'document.cookie'	=> '',
            'document.write'	=> '',
            '.parentNode'		=> '',
            '.innerHTML'		=> '',
            'window.location'	=> '',
            '-moz-binding'		=> '',
            '<!--'				=> '&lt;!--',
            '-->'				=> '--&gt;',
            '<![CDATA['			=> '&lt;![CDATA['
        );

        protected $_never_allowed_regex = array(
            "javascript\s*:"			=> '',
            "expression\s*(\(|&\#40;)"	=> '',
            "vbscript\s*:"				=> '',
            "Redirect\s+302"			=> ''
        );

        private static  $_data = null;

        public $config = array();

        private function __construct()
        {
            // do nothing
        }

        private function __clone()
        {
            // do nothing
        }

        /**
         * @param array $params
         * @param $key
         * @param bool $xss_filter - boolean used to applying cross site scripting
         * @return null|string
         */
        public static function param(array $params, $key, $xss_filter = false){
            if (isset($params[$key])){
                if ($xss_filter){
                    $class = new static();
                    return urldecode($class->xss_clean($params[$key]));
                }else{
                    return urldecode($params[$key]);
                }
            }else{
                return null;
            }
        }

        public static function get($key,$xss_filter = false, $default = "")
        {
            if (isset($_GET[$key]))
            {
                if ($xss_filter){
                    $class = new static();
                    return $class->xss_clean(self::$_data[$_POST[$key]]);
                }else{
                    return trim($_GET[$key]);
                }
            }
            return $default;
        }

        public static function post($key, $xss_filter = false, $default = "")
        {
            if (isset($_POST[$key]))
            {
                if ($xss_filter){
                    $class = new static();
                    return $class->xss_clean(self::$_data[$_POST[$key]]);
                }else{
                    return trim($_POST[$key]);
                }
            }
            return $default;
        }

        public static function server($key, $default = "")
        {
            if (isset($_SERVER[$key]))
            {
                return $_SERVER[$key];
            }
            return $default;
        }

        public static function cookie($key, $default = "")
        {
            if (isset($_COOKIE[$key]))
            {
                return $_COOKIE[$key];
            }
            return $default;
        }

        /**
         * Method Description
         *
         * @param
         * @param bool $xss_filter
         * @return array|bool|mixed
         */
        public static function file($var_name, $xss_filter = false) {
            //return $this->_fetch_from_array($_FILES, $var_name, $xss_filter);
        }

        /**
         * @param $var_name
         * @param bool $xss_filter
         * @return mixed
         */
        public static function json_raw($var_name, $xss_filter = false){
//            $data = new stdClass();
            $input = file_get_contents('php://input');
            self::$_data = json_decode(file_get_contents('php://input'));
            if(@property_exists( self::$_data, $var_name)){
                if($xss_filter) {

                    $class = new static();
                    return $class->xss_clean(self::$_data[$var_name]);
                }
                else {return  self::$_data->$var_name;}
            }else{
                return null;
            }
        }

        /**
         * @param $var_name
         * @param bool $xss_filter
         * @return mixed
         */
        public static function json_raw_assoc($var_name, $xss_filter = false){
//            $data = new stdClass();
            $input = file_get_contents('php://input');
            self::$_data = json_decode(file_get_contents('php://input'),true);
            if(isset( self::$_data[$var_name])){
                if($xss_filter) {
                    $class = new static();
                    return $class->xss_clean(self::$_data[$var_name]);
                }
                else {return  self::$_data[$var_name];}
            }else{
                return null;
            }
        }


        public static function json_put($var_name, $xss_filter = false){

//            if(@property_exists($this->$_data, $var_name)){
//                if($xss_filter) {return $this->xss_clean($this->$_data->$var_name);}
//                else {return $this->$_data->$var_name;}
//            }else{
//                return null;
//            }

        }

        public function get_stream(){
            $this->$_data = json_decode(file_get_contents('php://input'));
        }
        /**
         * Method Description
         *
         * @param
         * @param bool $is_image
         * @return array|bool|mixed
         */
        public function xss_clean($str, $is_image = FALSE) {
            if (is_array($str)) {
                while (list($key) = each($str)) { $str[$key] = $this->xss_clean($str[$key]); }

                return $str;
            }

            //$str = remove_invisible_characters($str);
            $str = $this->_validate_entities($str);
            $str = rawurldecode($str);
            $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
            $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, '_decode_entity'), $str);
            //$str = remove_invisible_characters($str);

            if (strpos($str, "\t") !== FALSE) { $str = str_replace("\t", ' ', $str); }

            $converted_string = $str;
            $str = $this->_do_never_allowed($str);
            if ($is_image === TRUE) { $str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str); }
            else { $str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str); }

            $words = array(
                'javascript', 'expression', 'vbscript', 'script',
                'applet', 'alert', 'document', 'write', 'cookie', 'window'
            );

            foreach ($words as $word) {
                $temp = '';

                for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++) {
                    $temp .= substr($word, $i, 1)."\s*";
                }

                $str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
            }

            do {
                $original = $str;

                if (preg_match("/<a/i", $str)) { $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_js_link_removal'), $str); }

                if (preg_match("/<img/i", $str)) {  $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_js_img_removal'), $str); }

                if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) { $str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '', $str); }
            } while($original != $str);

            unset($original);


            $str = $this->_remove_evil_attributes($str, $is_image);

            $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
            $str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);
            $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
            //$str = $this->_do_never_allowed($str);


            if ($is_image === TRUE) { return ($str == $converted_string) ? TRUE: FALSE; }

            return $str;
        }

        /**
         * Method Description
         *
         * @param
         * @return string
         */
        protected function _compact_exploded_words($matches) {
            return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
        }

        /**
         * Method Description
         *
         * @param
         * @return mixed
         */
        protected function _convert_attribute($match) {
            return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
        }

        /**
         * Method Description
         *
         * @param
         * @return mixed|string
         */
        protected function _decode_entity($match) {
            return $this->entity_decode($match[0], strtoupper($this->config_item('charset')));
        }

        /**
         * Method Description
         *
         * @param
         * @return mixed
         */
        protected function _js_link_removal($match) {
            $attributes = $this->_convert_attribute(str_replace(array('<', '>'), '', $match[1]));

            return str_replace($match[1], preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
        }

        /**
         * Method Description
         *
         * @param
         * @return mixed
         */
        protected function _js_img_removal($match) {
            $attributes = $this->_convert_attribute(str_replace(array('<', '>'), '', $match[1]));

            return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
        }

        /**
         * Method Description
         *
         * @param $str
         * @param $is_image
         * @internal param $
         * @return mixed
         */
        protected function _remove_evil_attributes($str, $is_image) {
            $evil_attributes = array('on\w*', 'style', 'xmlns');

            if ($is_image === TRUE) {
                unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
            }

            do {
                $str = preg_replace(
                    "#<(/?[^><]+?)([^A-Za-z\-])(".implode('|', $evil_attributes).")(\s*=\s*)([\"][^>]*?[\"]|[\'][^>]*?[\']|[^>]*?)([\s><])([><]*)#i",
                    "<$1$6",
                    $str, -1, $count
                );
            } while ($count);

            return $str;
        }

        /**
         * Method Description
         *
         * @param
         * @return mixed
         */
        protected function _validate_entities($str) {
            $str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->xss_hash()."\\1=\\2", $str);
            $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);
            $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);
            $str = str_replace($this->xss_hash(), '&', $str);

            return $str;
        }

        /**
         * Method Description
         *
         * @param
         * @return mixed
         */
        protected function _do_never_allowed($str) {
            foreach ($this->_never_allowed_str as $key => $val) {
                $str = str_replace($key, $val, $str);
            }

            foreach ($this->_never_allowed_regex as $key => $val) {
                $str = preg_replace("#".$key."#i", $val, $str);
            }

            return $str;
        }

        /**
         * Method Description
         *
         * @param
         * @param string $charset
         * @return mixed|string
         */
        public function entity_decode($str, $charset='UTF-8') {
            if (stristr($str, '&') === FALSE) return $str;

            if (function_exists('html_entity_decode') &&
                (strtolower($charset) != 'utf-8'))
            {
                $str = html_entity_decode($str, ENT_COMPAT, $charset);
                $str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
                return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
            }

            $str = preg_replace('~&#x(0*[0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1"))', $str);
            $str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);

            if (stristr($str, '&') === FALSE) {
                $str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
            }

            return $str;
        }

        /**
         * Method Description
         *
         * @internal param $
         * @return string
         */
        public function xss_hash() {
            if ($this->_xss_hash == '') {
                if (phpversion() >= 4.2) {
                    mt_srand();
                } else {
                    mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
                }

                $this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
            }

            return $this->_xss_hash;
        }

        /**
         * Method Description
         *
         * @param
         * @param string $index
         * @return bool
         */
        function config_item($item, $index = '') {
            if ($index == '') {
                if ( ! isset($this->config[$item])) {
                    return FALSE;
                }

                $pref = $this->config[$item];
            } else {
                if ( ! isset($this->config[$index])) {
                    return FALSE;
                }

                if ( ! isset($this->config[$index][$item])) {
                    return FALSE;
                }

                $pref = $this->config[$index][$item];
            }

            return $pref;
        }
    }
}
 