<?php
/**
 *
 */
echo 'test.php';

class User extends Framework\Model
{
    /**
     * @column
     * @readwrite
     * @primary
     * @type autonumber
     */
    protected $_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_first;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_last;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_email;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_password;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_notes;

    /**
     * @column
     * @readwrite
     * @type boolean
     * @index
     */
    protected $_live;

    /**
     * @column
     * @readwrite
     * @type boolean
     * @index
     */
    protected $_deleted;

    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_created;

    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_modified;
}
$database = new Framework\Database(array(
    "type" => "mysql",
    "options" => array(
        "host" => "localhost",
        "username" => "root",
        "password" => "root",
        "schema" => "prophpmvc"
    )
));
$database = $database->initialize()->connect();

$user = new User(array(
    "connector" => $database
));
$database->sync($user);