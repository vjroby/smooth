<?php

namespace Framework
{
    use Framework\Language\Exception;

    class Language extends Base{
        /**
         * @readwrite
         */
        protected $_languageCodes = array(
            "aa" => "afar",
            "ab" => "abkhazian",
            "ae" => "avestan",
            "af" => "afrikaans",
            "ak" => "akan",
            "am" => "amharic",
            "an" => "aragonese",
            "ar" => "arabic",
            "as" => "assamese",
            "av" => "avaric",
            "ay" => "aymara",
            "az" => "azerbaijani",
            "ba" => "bashkir",
            "be" => "belarusian",
            "bg" => "bulgarian",
            "bh" => "bihari",
            "bi" => "bislama",
            "bm" => "bambara",
            "bn" => "bengali",
            "bo" => "tibetan",
            "br" => "breton",
            "bs" => "bosnian",
            "ca" => "catalan",
            "ce" => "chechen",
            "ch" => "chamorro",
            "co" => "corsican",
            "cr" => "cree",
            "cs" => "czech",
            "cu" => "church Slavic",
            "cv" => "chuvash",
            "cy" => "welsh",
            "da" => "danish",
            "de" => "german",
            "dv" => "divehi",
            "dz" => "dzongkha",
            "ee" => "ewe",
            "el" => "ereek",
            "en" => "english",
            "eo" => "esperanto",
            "es" => "spanish",
            "et" => "estonian",
            "eu" => "basque",
            "fa" => "persian",
            "ff" => "fulah",
            "fi" => "finnish",
            "fj" => "fijian",
            "fo" => "faroese",
            "fr" => "french",
            "fy" => "western Frisian",
            "ga" => "irish",
            "gd" => "scottish gaelic",
            "gl" => "galician",
            "gn" => "guarani",
            "gu" => "gujarati",
            "gv" => "manx",
            "ha" => "mausa",
            "he" => "hebrew",
            "hi" => "hindi",
            "ho" => "hiri Motu",
            "hr" => "croatian",
            "ht" => "haitian",
            "hu" => "hungarian",
            "hy" => "armenian",
            "hz" => "herero",
            "ia" => "interlingua",
            "id" => "indonesian",
            "ie" => "interlingue",
            "ig" => "igbo",
            "ii" => "sichuan yi",
            "ik" => "inupiaq",
            "io" => "ido",
            "is" => "icelandic",
            "it" => "italian",
            "iu" => "inuktitut",
            "ja" => "japanese",
            "jv" => "javanese",
            "ka" => "georgian",
            "kg" => "kongo",
            "ki" => "kikuyu",
            "kj" => "kwanyama",
            "kk" => "kazakh",
            "kl" => "kalaallisut",
            "km" => "khmer",
            "kn" => "kannada",
            "ko" => "korean",
            "kr" => "kanuri",
            "ks" => "kashmiri",
            "ku" => "kurdish",
            "kv" => "komi",
            "kw" => "cornish",
            "ky" => "kirghiz",
            "la" => "latin",
            "lb" => "luxembourgish",
            "lg" => "ganda",
            "li" => "limburgish",
            "ln" => "lingala",
            "lo" => "lao",
            "lt" => "lithuanian",
            "lu" => "luba-Katanga",
            "lv" => "latvian",
            "mg" => "malagasy",
            "mh" => "marshallese",
            "mi" => "maori",
            "mk" => "macedonian",
            "ml" => "malayalam",
            "mn" => "mongolian",
            "mr" => "marathi",
            "ms" => "malay",
            "mt" => "maltese",
            "my" => "burmese",
            "na" => "nauru",
            "nb" => "norwegian bokmal",
            "nd" => "north ndebele",
            "ne" => "nepali",
            "ng" => "ndonga",
            "nl" => "dutch",
            "nn" => "norwegian nynorsk",
            "no" => "norwegian",
            "nr" => "south ndebele",
            "nv" => "navajo",
            "ny" => "chichewa",
            "oc" => "occitan",
            "oj" => "ojibwa",
            "om" => "oromo",
            "or" => "oriya",
            "os" => "ossetian",
            "pa" => "panjabi",
            "pi" => "pali",
            "pl" => "polish",
            "ps" => "pashto",
            "pt" => "portuguese",
            "qu" => "quechua",
            "rm" => "raeto-Romance",
            "rn" => "kirundi",
            "ro" => "romanian",
            "ru" => "russian",
            "rw" => "kinyarwanda",
            "sa" => "sanskrit",
            "sc" => "sardinian",
            "sd" => "sindhi",
            "se" => "northern sami",
            "sg" => "sango",
            "si" => "sinhala",
            "sk" => "slovak",
            "sl" => "slovenian",
            "sm" => "samoan",
            "sn" => "shona",
            "so" => "somali",
            "sq" => "albanian",
            "sr" => "serbian",
            "ss" => "swati",
            "st" => "southern sotho",
            "su" => "sundanese",
            "sv" => "swedish",
            "sw" => "swahili",
            "ta" => "tamil",
            "te" => "telugu",
            "tg" => "tajik",
            "th" => "thai",
            "ti" => "tigrinya",
            "tk" => "turkmen",
            "tl" => "tagalog",
            "tn" => "tswana",
            "to" => "tonga",
            "tr" => "turkish",
            "ts" => "tsonga",
            "tt" => "tatar",
            "tw" => "twi",
            "ty" => "tahitian",
            "ug" => "uighur",
            "uk" => "ukrainian",
            "ur" => "urdu",
            "uz" => "uzbek",
            "ve" => "venda",
            "vi" => "vietnamese",
            "vo" => "volapuk",
            "wa" => "walloon",
            "wo" => "wolof",
            "xh" => "xhosa",
            "yi" => "yiddish",
            "yo" => "yoruba",
            "za" => "zhuang",
            "zh" => "chinese",
            "zu" => "zulu"
        );

        /**
         * @readwrite
         */
        protected $_languageDir;

        public function __construct(){
            parent::__construct();

            if (empty($this->_languageDir)){
                $this->_languageDir = APP_PATH.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'languages'.DIRECTORY_SEPARATOR;
            }
        }

        public function __set($name, $value){
            preg_match('#_txt$#',$name,$matches);
            if(count($matches) == 1){
                $this->$name = $value;
            }else{
                parent::__set($name, $value);
            }
        }

        public function __get($name){
            preg_match('#_txt$#',$name,$matches);
            if(count($matches) == 1){
                if (isset($this->$name)){
                    return $this->$name;

                }else{
                    throw new Exception('No string in xml language file');
                }
            }else{
                parent::__get($name);
            }
        }

        /**
         * Method Description
         *
         * @param $xml
         * @return array
         */
        private function parse_xml($xml) {
            $return = array();
            foreach((array)$xml as $name => $val) {
                if(!is_object($val)) {
                    //echo gettype($val);
                    $name = preg_match("/@/", $name, $matches, PREG_OFFSET_CAPTURE) ? substr($name, 1) : $name;
                    $return[$name] = $val;
                } else {
                    // For language file we do not parse any other arrays, just the ROOT;
                    //$return[$name] = $this->parse_xml($val);
                }
            }
            return $return;
        }

        /**
         * Method Description
         *
         * @return string
         */
        private function get_browser_locale() {
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $string = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                if(strlen($string)>1) {
                    $lang_info = explode(",", $string);
                    return strtolower($lang_info[0]);
                }
            } else {
                if(defined("DEFAULT_LANGUAGE")) {
                    return strtolower(DEFAULT_LANGUAGE);
                }
            }
        }

        /**
         * Method Description
         *
         * @param $locale
         * @throws language\Exception
         * @return array
         */
        private function load_file($locale) {

            $file = false;
            $languageDir = $this->_languageDir;

            // Try to find out if the current given local exists in a file name or by the format: en-us ori by trimming and getting just: en.
            if(file_exists($languageDir.$locale.".xml")) {
                $file = $locale.".xml";
            }
            else if(file_exists($languageDir.reset(explode("-", $locale)).".xml")) {
                @$file = reset(explode("-", $locale)).".xml";
            }
            else if(defined("DEFAULT_LANGUAGE")) {
                // If we do not find the user given locale we have a fallback, retrive the defined default language file.
                if(file_exists($languageDir.strtolower(DEFAULT_LANGUAGE).".xml")) {
                    @$file = $languageDir.strtolower(DEFAULT_LANGUAGE).".xml";
                }
                else {
                    $file = false;
                }
            }
            if($file != false) {
                $content = file($languageDir.$file);
                if(preg_match("/<?xml/", $content[0], $matches, PREG_OFFSET_CAPTURE)) {
                    $xml = simplexml_load_file($languageDir.$file);
                    $parsedXML =  $this->parse_xml($xml);

                    return $parsedXML;
                }else{
                    throw new Exception\File('The language file is not xml formated');
                }
            }else{
                throw new Exception\File('No language file!');
            }
        }

        //--------------------------------------------------------------------------------------------
        // language->load()
        //--------------------------------------------------------------------------------------------
        // With this function the developer loads an language file and parses it into variables
        // for easy access truought the entire framework.
        // You can give the locale in the load function and it will overwrite the users' browser
        // language or the default language of the website.
        // If the given locale is not found in a file language->load_file() will fallback to
        // loading the default language file defined in the config file.
        //--------------------------------------------------------------------------------------------
        public function load($language = null) {
            if(!is_null($language)) {
                $getLocale = $this->get_locale_from_string($language);
                if($getLocale == false) {
                    $locale = strtolower($language);
                }
                else { $locale = $getLocale; }
            }
            else {
                $locale = $this->get_browser_locale();
            }

            if(isset($locale)) {
                setlocale(LC_ALL , $locale); // set php locale
                $xml = $this->load_file($locale);

                foreach($xml as $name => $value) {
                    $this->$name = $value;
                }
                return $this;
            }else{
                throw new Exception('The locale is not set');
            }
        }

        /**
         * Method Description
         *
         * @param $language
         * @return bool|mixed
         */
        public function get_locale_from_string($language) {
            $languageCodes = $this->_languageCodes;
            if(array_key_exists($language, $languageCodes)) {
                return $language;
            } else {
                if($locale = array_search(strtolower($language), $languageCodes)) {
                    return $locale;
                } else {
                    return false;
                }
            }
        }

        /**
         * Method Description
         *
         * @return array
         */
        public function get_available_languages() {
            $lang = $this->languageDir;
            $languageDir = scandir($lang);

            $languages = array();

            foreach($languageDir as $file) {
                if(strtolower(end(@explode('.', $file))) == 'xml') {
                    $languages[] = strtolower(reset(@explode('.', $file)));
                }
            }


            return $languages;
        }
    }
}
 