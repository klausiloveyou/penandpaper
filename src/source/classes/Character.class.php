<?php
/**
 * Created by IntelliJ IDEA.
 * User: felix.reinhardt
 * Date: 2019-01-06
 * Time: 01:32
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";

class Character
{
    private $id;
    private $name;

    /**
     * Character constructor.
     * @param $cid
     * @throws Exception
     */
    public function __construct($cid)
    {
        try {
            $char = self::getCharDocumentByID($cid);
            if (is_null($char)) {
                throw new Exception("No character found with this ID.");
            } else {
                $this->name = $char->name;
                $this->id = (string) $char->_id;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $cid
     * @return stdClass|null
     * @throws Exception
     */
    public static function getCharDocumentByID($cid)
    {
        $query = ['_id' => new MongoDB\BSON\ObjectId($cid)];
        try {
            $char = queryDocument("pnp.char", $query);
            return (is_null($char)) ? null : $char[0];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}