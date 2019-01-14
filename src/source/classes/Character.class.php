<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";

/**
 * Class handles character data fetched from and stored in the MongoDB for a single document (there is much to store).
 *
 * MongoDB pnp.char document:
 *
 *  {
 *      "_id": "objectId",
 *      "user_id": "objectId",
 *      "profile": {
 *          "name": "string",
 *          "race": "string",
 *          "career": "array",
 *          "age": "string",
 *          "gender": "string",
 *          "eyecolor": "string",
 *          "haircolor": "string",
 *          "height": "string",
 *          "weight": "string",
 *          "starsign": "string",
 *          "numberofsiblings": "string",
 *          "birthplace": "string",
 *          "distinguishingmarks": "string",
 *          "mainstarting": "array",
 *          "mainadvance": "array",
 *          "maincurrent": "array",
 *          "secstarting": "array",
 *          "secadvance": "array",
 *          "seccurrent": "array"
 *      },
 *      "armory": {
 *      },
 *      "skills": {
 *      },
 *      "talents": {
 *      },
 *      "belongins": {
 *      },
 *      "spells": {
 *      }
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
    /** @var stdClass $profile */
    private $profile;

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
                $this->profile = $char->profile;
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
     * Helper method to create and store a new character for given user.
     *
     * @param MongoDB\BSON\ObjectId $uid
     * @param stdClass $chardata
     * @return MongoDB\BSON\ObjectId
     * @throws Exception
     * @access public
     * @static
     */
    public static function create($uid, $chardata)
    {
        /** @var string $charName */
        $charName = $chardata->profile->name;
        try {
            if (!self::doesCharacterNameExistsForUser($uid, $charName)) {
                $new_char = (object) array_merge( (array) $chardata, array( 'user_id' => $uid ) );
                bulkInsertOne(self::DBNAMESPACE, $new_char);
                $query = ['user_id' => $uid, 'profile.name' => $charName ];
                $options = [ 'projection' => [ '_id' => 1 ] ];
                $rs = queryDocument(self::DBNAMESPACE, $query, $options);
                return $rs[0]->_id;
            } else {
                throw new Exception("You already have a character with name: ".$charName."<br/>Choose a another name and click \"create\" again.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Public static method to check if a user already has a character with the name.
     * Could be also solved by creating a unique index constraint.
     *
     * @param MongoDB\BSON\ObjectId $uid
     * @param string $name
     * @return bool
     * @throws Exception
     * @access public
     * @static
     */
    public static function doesCharacterNameExistsForUser($uid, $name)
    {
        if (trim($name) == false) {
            return true;
        }
        $query = [
            'user_id' => $uid,
            'profile.name' => new MongoDB\BSON\Regex("^".$name."$", "i")
        ];
        try {
            $char = queryDocument(self::DBNAMESPACE, $query);
            return (is_null($char)) ? false : true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Public method to update the character with given data.
     *
     * @param stdClass $chardata
     * @throws Exception
     * @access public
     */
    public function update($chardata)
    {
        $new = [];
        foreach ($chardata as $fieldset => $value) {
            $new[$fieldset] = $value;
        }
        $update = [
            '$set' => $new
        ];
        try {
            bulkUpdateOneByID(self::DBNAMESPACE, $this->id, $update);
            /** @var User $user */
            $user = $_SESSION["user"];
            $user->setLastUsed($this->id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Public method to fetch data from the character that is send back in JSON format for character API calls.
     *
     * @param string $what
     * @return stdClass[]
     * @throws Exception
     * @access public
     */
    public function fetchJSONData($what)
    {
        $what = strtolower(trim($what));
        if (!empty($what)) {
            $projection = [ '_id' => 0, 'user_id' => 0 ];
            if ($what !== "*") {
                $projection = [ '_id' => 0, $what => 1 ];
            }
            $query = ['_id' => $this->getID()];
            $options = [ 'projection' => $projection ];
            try {
                $result = queryDocument(self::DBNAMESPACE, $query, $options);
                if (is_null($result)) {
                    throw new Exception("No data found for \"".$what."\" and character \"".$this->getProfile()->name."\".");
                }
                return $result[0];
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("Provide what you want to fetch. Using \"*\" will return all data.");
        }
    }

    /**
     * Getter method to return private $name property.
     *
     * @return stdClass
     * @access public
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Setter method to set private $name property.
     * Also updates document in collection db.char for: "name": "string"
     *
     * @param stdClass $newprofile
     * @access public
     *
     * @todo Implement DB update
     */
    public function setProfile($newprofile)
    {
        $this->profile = $newprofile;
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