<?php

namespace App\Model
{
    use \Framework\Model as Model;
    class Example extends Model{

        /**
         * @readwrite
         * @column
         * @type autonumber
         * @primary
         */
        protected $_id;

        /**
         * @readwrite
         * @column
         * @type text
         * @length 32
         */
        protected $_name;

        /**
         * @readwrite
         * @column
         * @type datetime
         */
        protected $_created;
    }
}
 