<?php

namespace Framework\Router\Route
{
    use Framework\HttpRequest;
    use Framework\Router;
    use Framework\Utility\ArrayMethods;

    class Api extends Router\Route{


        public function matches($url){
            $pattern = $this->_pattern;

            $httpRequestMethod = HttpRequest::getRequestMethod();
            if ($httpRequestMethod === $this->_httpMethod){
                //\Framework\Smooth::$_cleanRequest = true;

                // check if the same method is in route added with api

                // get keys
                preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);

                if (count($keys) && count($keys[0]) && count($keys[1])){
                    $keys = $keys[1];
                }else{
                    // no keys in the pattern , return a simple match
                    return preg_match("#^{$pattern}$#", $url);
                }
                // normalize route pattern
                $pattern = preg_replace("#(:[a-zA-Z0-9]+)#", "([a-zA-Z0-9-_]+)", $pattern);

                // check values
                preg_match_all("#^{$pattern}$#", $url, $values);

                if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])){
                    // unset the matched url
                    unset($values[0]);
                    $new_val = array();
                    foreach ($values as $key => $val) {
                       if (isset($val[0])){
                           $new_val[] = $val[0];
                       }
                    }

                    // values found, modify parameters and return
                    $derived = array_combine($keys, ArrayMethods::flatten($new_val));
                    $this->_parameters = array_merge($this->_parameters, $derived);

                    return true;
                }
            }
        }

    }
}
 