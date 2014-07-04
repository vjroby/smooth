<?php
class User extends \Shared\Model{

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
     * @length 100
     * @index
     */
    protected $_notes;

    /**
     * @column
     * @readwrite
     * @type integer
     * @length 4
     * @index
     */
//    protected $_live;

    /**
     * @column
     * @readwrite
     * @type boolean
     */
    protected $_admin=false;

    /**
     * @readwrite
     */
    protected $_fileimage;

    public function isFriend($id)
    {
        $userId = $this->getId();

        $friend = Friend::first(array(
            "user = ?" => $this->getId(),
            "friend = ?" => $id
        ));

        if ($friend)
        {
            return true;
        }
        return false;
    }

    public static function hasFriend($id, $friend)
    {
        $user = new self(array(
            "id" => $id
        ));

        return $user->isFriend($friend);
    }

    public function getFileimage(){
        return File::first(array(
            "user = ?" =>$this->id, "live = ?" =>true, "deleted = ?" =>false ,
        ), array("*"), "id", "DESC");
    }


}
 