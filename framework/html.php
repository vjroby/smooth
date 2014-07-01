<?php
namespace Framework
{
    use Framework\Base as Base;
    use Framework\Html\Exception as Exception;
    use Framework\Utility\ArrayMethods;
    use Framework\Utility\StringMethods;

    class Html extends Base{

        /**
         * Builds a HTML select element with options
         * @param $options['data']
         * @param $options['disabled']
         * @param $options['class']
         * @param $options['name']
         * @param $options['id']
         * @param $options['value'] - selected value
         * @param $options['default'] - default value
         *
         * @param array $options
         * @return string
         * @throws Html\Exception
         *
         */
        public static function select(array $options){
            $options = ArrayMethods::prepareForHtml($options);
            if (!isset($options['data']) || !is_array($options['data'])){
                throw new Exception('$options[data] not provided or is not an array');
            }
            $disabled = (isset($options['disabled']) && is_bool($options['disabled']))
                ? $options['disabled'] : false;

            $readonly = (isset($options['readonly']) && is_bool($options['readonly']))
                ? $options['readonly'] : false;

            $return ='';

            $return = self::createLabelForInput($options);

            $return .= '<select ';
            $return .= ' class="'.self::checkOption($options,'class').'" ';
            $return .= ' name="'.self::checkOption($options,'name').'" ';
            $return .= ' id="'.self::checkOption($options,'id').'" ';
            if ($disabled === true){
                $return .= ' disabled ';
            }

            if ($readonly === true){
                $return .= ' readonly ';
            }
            $return .= ' >';
            $selected_value = self::checkOption($options,'value');
            $default_value = self::checkOptionReturnNull($options, 'default');
            if (!is_null($default_value)){
                $return .= '<option value="default">'.$default_value.'</option>';
            }
            foreach ($options['data'] as $key => $value) {

                $selected = $key == $selected_value
                    ? strlen($selected_value) !=0
                        ? 'selected'
                        :''
                    :
                    '' ;

                $return .= ' <option value="'.$key.'" '.$selected.'>'.$value.'</option> ';
            }


            $return .= '</select>';

            return $return;
        }

        /**
         * @param array $options
         * @return string
         */
        public static function input(array $options){
            $options = ArrayMethods::prepareForHtml($options);
            $disabled = (isset($options['disabled']) && is_bool($options['disabled']))
                ? $options['disabled'] : false;

            $readonly = (isset($options['readonly']) && is_bool($options['readonly']))
                ? $options['readonly'] : false;


            $return = self::createLabelForInput($options);

            $return .= '<input ';

            $return .= ' class="'.self::checkOption($options,'class').'" ';
            $return .= ' name="'.self::checkOption($options,'name').'" ';
            $return .= ' id="'.self::checkOption($options,'id').'" ';
            $return .= ' value="'.self::checkOption($options,'value').'" ';
            $return .= ' type="'.self::checkOption($options,'type').'" ';

            if ($disabled === true){
                $return .= ' disabled ';
            }

            if ($readonly === true){
                $return .= ' readonly ';
            }

            $return.= ' />';

            $return = self::apply_wrapper($options, $return);

            return $return;
        }

        public static function textarea(array $options){
            $options = ArrayMethods::prepareForHtml($options);
            $disabled = (isset($options['disabled']) && is_bool($options['disabled']))
                ? $options['disabled'] : false;

            $readonly = (isset($options['readonly']) && is_bool($options['readonly']))
                ? $options['readonly'] : false;


            $return = self::createLabelForInput($options);

            $return .= '<textarea ';
            $return .= ' class="'.self::checkOption($options,'class').'" ';
            $return .= ' name="'.self::checkOption($options,'name').'" ';
            $return .= ' id="'.self::checkOption($options,'id').'" ';
            $return .= ' type="'.self::checkOption($options,'type').'" ';

            if ($disabled === true){
                $return .= ' disabled ';
            }

            if ($readonly === true){
                $return .= ' readonly ';
            }

            $return .= ' > ';
            $return .= ' '.self::checkOption($options,'value').' ';
            $return .= '</textarea>';


            $return = self::apply_wrapper($options, $return);

            return $return;

        }

        /**
         * Creates label for form element
         *
         * @param $options
         * @return string
         */
        public static function createLabelForInput($options){
            $return = '';
            if (!is_null($label = self::checkOptionReturnNull($options, 'label'))){
                if ($label !== false){


                    $return .= ' <label for="'.self::checkOption($options, 'name').'"';
                    $return .= 'class="'.self::checkOption($label,'class').'"';
                    $return .= '>'.self::checkOption($label, 'title').'</label> ';
                }
            }
            return  $return;
        }

        /**
         *
         * Checks if an key exists in $options array and if is null returns it,
         * otherwise returns an empty string
         *
         * @param array $options
         * @param $key
         * @return string
         */
        protected static function  checkOption(array $options, $key){
            if (isset($options[$key]) && !is_null($key) && !empty($key)){
                return $options[$key];
            }else{
                return '';
            }
        }
        /**
         *
         * Checks if an key exists in $options array and if is null returns it,
         * otherwise returns an null
         *
         * @param array $options
         * @param $key
         * @return string
         */
        protected static function  checkOptionReturnNull(array $options, $key){
            if (isset($options[$key]) && !is_null($key) && !empty($key)){
                return $options[$key];
            }else{
                return null;
            }
        }

        /**
         * creates a wrapper to put the element in
         *
         * @param array $options
         * @param $string - is the string form element
         * @return string
         */
        protected static function apply_wrapper(array $options, $string){

            $wrapper_tag = 'div';
            $wrapper_class = '';
            $wrapper_id = '';

            if(isset($options['wrapper']) && is_array($options['wrapper'])){
                $wrapper_tag = isset($options['wrapper']['type']) ? $options['wrapper']['type']
                    : $wrapper_tag;

                $wrapper_class = isset($options['wrapper']['class']) ? $options['wrapper']['class']
                    : $wrapper_class;

                $wrapper_id = isset($options['wrapper']['id']) ? $options['wrapper']['id']
                    : $wrapper_id;


            }elseif(isset($options['wrapper']) && $options['wrapper'] === false){
                $wrapper_tag = false;
            }

            if($wrapper_tag !== false){
                return "<{$wrapper_tag} class=\"{$wrapper_class}\" id=\"{$wrapper_id}\">".$string."</{$wrapper_tag}>";
            }else{
                return $string;
            }
        }
    }

}
 