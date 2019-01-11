<?php
/**
 * Util methods for DB operations
 *
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/Character.class.php";

/**
 * Class handles user data fetched from and stored in the MongoDB for a single document.
 *
 * MongoDB db.user document:
 *
 *  {
 *      "_id": "objectId",
 *      "name": "string",
 *      "pwd": {
 *          "hash": "string",
 *          "temp": "bool"
 *      },
 *      "role": "string",
 *      "lastUsed": "objectId"
 *  }
 *
 */
class User
{
    const DBNAMESPACE = DB_DB.".user";

    /** @var MongoDB\BSON\ObjectId $id */
    private $id;
    /** @var string $name */
    private $name;
    /** @var stdClass $pwd */
    private $pwd;
    /** @var Character $lastUsed */
    private $lastUsed;
    /** @var string $role */
    private $role;
    /** @var MongoDB\BSON\ObjectId[] $charObjectIDs */
    private $charObjectIDs;

    /**
     * User constructor. Use User::login() in order to create a new instance.
     *
     * @param stdClass $user
     * @see User::login()
     * @access private
     */
    private function __construct($user)
    {
        $this->id = $user->_id;
        $this->name = $user->name;
        $this->pwd = $user->pwd;
        $this->role = $user->role;
        try {
            $this->lastUsed = (is_null($user->lastUsed)) ? null : new Character($user->lastUsed);
        } catch (Exception $e) {
            $this->lastUsed = null;
        }
        try {
            $this->refreshCharObjectIDs();
        } catch (Exception $e) {
            $this->charObjectIDs = null;
        }
    }

    /**
     * Method to verify login data for a user and return a new instance or return an exception.
     * Only method to create a new User instance by calling the private constructor.
     *
     * @param string $name
     * @param string $pw
     * @return User
     * @throws Exception if user or password checks failed
     * @access public
     * @static
     */
    public static function login($name, $pw)
    {
        $query = ['name' => new MongoDB\BSON\Regex("^".$name."$", "i")];
        try {
            $result = queryDocument(self::DBNAMESPACE, $query);
        } catch (Exception $e) {
            throw new Exception("user");
        }
        if (is_null($result)) {
            throw new Exception("user");
        } else {
            $user = $result[0];
        }
        if (!password_verify($pw, $user->pwd->hash)) {
            throw new Exception("pw");
        }
        return new self($user);
    }

    /**
     * Method in order to create a new user within the db.user collection (persistent).
     * Does not return a new User instance but trows an exception on error.
     *
     * @param string $name
     * @param string $pw
     * @throws Exception
     * @access public
     * @static
     */
    public static function create($name, $pw)
    {
        if (!self::doesUserNameExist($name)) {
            $new_user = [
                'name' => $name,
                'pwd' => [
                    'hash' => password_hash($pw, PASSWORD_DEFAULT),
                    'temp' => true
                ],
                'role' => 'user',
                'lastUsed' => null
            ];
            try {
                bulkInsertOne(self::DBNAMESPACE, $new_user);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("User name exists already.");
        }
    }

    /**
     * Helper method in order to check if a user already exists with a given name.
     * Could be also solved by using a unique index constraint at the MongoDB.
     *
     * @param string $name The user name to check if it exists already
     * @return bool User name exists or not
     * @throws Exception
     * @access public
     * @static
     */
    public static function doesUserNameExist($name)
    {
        $query = ["name" => new MongoDB\BSON\Regex("^".$name."$", "i")];
        try {
            $result = queryDocument(self::DBNAMESPACE, $query);
            return (is_null($result)) ? false : true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Method to set a new password if the current password was validated.
     *
     * @param string $old
     * @param string $new
     * @throws Exception
     * @access public
     */
    public function changePassword($old, $new)
    {
        if (password_verify($old, $this->pwd->hash)) {
            $pwd = [
                'hash' => password_hash($new, PASSWORD_DEFAULT),
                'temp' => false
            ];
            try {
                $this->setPwd($pwd);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("Current password was invalid.");
        }
    }

    /**
     * @param string $serialized_cid
     * @return bool
     */
    private function isCharacterFromUser($serialized_cid)
    {
        foreach ($this->getCharObjectIDs() as $cid) {
            if ($cid->serialize() === $serialized_cid) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get method to return the private array which holds all character IDs belonging to the user:
     * Referring to "_id": "objectID" for document in db.char
     *
     * Also refreshes the character IDs to ensure including newly created characters as well.
     *
     * @return MongoDB\BSON\ObjectId[]|null
     * @access public
     * @see User->refreshCharObjectIDs()
     */
    public function getCharObjectIDs()
    {
        try {
            $this->refreshCharObjectIDs();
        } catch (Exception $e) {
            error_log($e->getMessage());
        } finally {
            return $this->charObjectIDs;
        }
    }

    /**
     * Method to renew private array $charIDs which includes all associated characters.
     * Also updates the User instance stored in the $_SESSION.
     *
     * @throws Exception
     * @access public
     */
    public function refreshCharObjectIDs()
    {
        $query = ['user_id' => $this->id];
        $options = [ 'projection' => ['_id' => 1] ];
        try {
            $chars = queryDocument(Character::DBNAMESPACE, $query, $options);
            if (is_null($chars)) {
                $this->charObjectIDs = null;
            } else {
                $this->charObjectIDs = [];
                foreach ($chars as $c) {
                    array_push($this->charObjectIDs, $c->_id);
                }
            }
            $_SESSION["user"] = $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Getter method to return private $role property.
     *
     * @return string
     * @access public
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Setter method to set private $role property.
     *
     * @param string $role
     * @throws Exception
     * @access public
     */
    public function setRole($role)
    {
        $new_role = [
            '$set' => [
                'role' => $role
            ]
        ];
        try {
            bulkUpdateOneByID(self::DBNAMESPACE, $this->id, $new_role);
            $this->role = $role;
            $_SESSION["user"] = $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Getter method to return private $lastUsed property which contains the last Character object.
     * Also tries to update the property if it is null and if there are any characters for the user associated.
     *
     * @return Character|null
     * @access public
     * @see User->setLastUsed()
     */
    public function getLastUsed()
    {
        if (is_null($this->lastUsed)) {
            if (!is_null($this->getCharObjectIDs())) {
                try {
                    $this->setLastUsed($this->charObjectIDs[0]);
                } catch (Exception $e) {
                    return $this->lastUsed;
                }
            }
        }
        return $this->lastUsed;
    }

    /**
     * Setter method to set private $role property and to update the document in db.user.
     * If successful, also updates the user stored in $_SESSION or throws an exception otherwise.
     *
     * @param MongoDB\BSON\ObjectId $cid
     * @throws Exception
     * @access public
     */
    public function setLastUsed($cid)
    {
        if ($this->isCharacterFromUser($cid->serialize())) {
            try {
                $char = new Character($cid);
                $new_char = [
                    '$set' => [
                        'lastUsed' => $char->getId()
                    ]
                ];
                bulkUpdateOneByID(self::DBNAMESPACE, $this->id, $new_char);
                $this->lastUsed = $char;
                $_SESSION["user"] = $this;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("Character is not associated with user.");
        }
    }

    /**
     * Getter method to get private $pwd property.
     *
     * @return stdClass
     * @access public
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * Setter method to set private $role property and to update the document in db.user.
     * If successful, also updates the user stored in $_SESSION or throws an exception otherwise.
     *
     * @param array $pwd
     * @throws Exception
     * @access public
     */
    public function setPwd($pwd)
    {
        $new_pw = [
            '$set' => [
                'pwd' => $pwd
            ]
        ];
        try {
            bulkUpdateOneByID(self::DBNAMESPACE, $this->id, $new_pw);
            $this->pwd = $pwd;
            $_SESSION["user"] = $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Getter method to get private $name property.
     * No setter method because the name of a user should no be changeable.
     *
     * @return string
     * @access public
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter method to get private $name property.
     * No setter method because the name of a user should no be changeable.
     *
     * @return MongoDB\BSON\ObjectId
     * @access public
     */
    public function getId()
    {
        return $this->id;
    }
}