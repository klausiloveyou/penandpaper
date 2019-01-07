<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";

/**
 * Class handles character data fetched from and stored in the MongoDB for a single document.
 *
 * MongoDB pnp.char document:
 *
 *  {
 *      "_id": "objectId",
 *      "user_id": "objectId",
 *      "name": "string"
 *  }
 *
 */
class Character
{
    const DBNAMESPACE = DB_DB.".char";

    /** @var MongoDB\BSON\ObjectId $id */
    private $id;
    /** @var MongoDB\BSON\ObjectId $uid */
    private $uid;
    /** @var string $name */
    private $name;

    /**
     * Character constructor.
     * Creates a new instance based on "_id": "objectId" of a document or throws an exception if no document found.
     *
     * @param MongoDB\BSON\ObjectId $cid
     * @throws Exception
     * @access public
     */
    public function __construct($cid)
    {
        try {
            $char = self::getCharDocumentByID($cid);
            if (is_null($char)) {
                throw new Exception("No character found for this ID.");
            } else {
                $this->id = $char->_id;
                $this->uid = $char->uid;
                $this->name = $char->name;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Helper method to fetch a document by its ID from the db.char collection.
     *
     * @param MongoDB\BSON\ObjectId $cid
     * @return stdClass|null
     * @throws Exception
     * @access public
     * @static
     */
    public static function getCharDocumentByID($cid)
    {
        $query = ['_id' => $cid];
        try {
            $char = queryDocument(self::DBNAMESPACE, $query);
            return (is_null($char)) ? null : $char[0];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Getter method to return private $name property.
     *
     * @return string
     * @access public
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter method to set private $name property.
     * Also updates document in collection db.char for: "name": "string"
     *
     * @param string $name
     * @access public
     *
     * @todo Implement DB update
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Getter method to return private $id property representing the ID of the document in collection db.char.
     * This has no setter method since it is created on document creation.
     *
     * @return MongoDB\BSON\ObjectId
     * @access public
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Getter method to return private $uid property representing the objectId of the associated user.
     * This has no setter method since it is created on document creation, can only have 1 associated user.
     *
     * @return MongoDB\BSON\ObjectId
     * @access public
     */
    public function getUID()
    {
        return $this->uid;
    }
}