<?php
/**
 * Created by IntelliJ IDEA.
 * User: felix.reinhardt
 * Date: 2019-01-05
 * Time: 14:42
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";

class User
{
    private $id;
    private $name;
    private $pwd;
    private $lastUsed;
    private $role;
    private $charIDs;

    /**
     * User constructor.
     * @param $user
     * @throws Exception
     */
    private function __construct($user)
    {
        $this->id = (string) $user->_id;
        $this->name = $user->name;
        $this->pwd = $user->pwd;
        $this->role = $user->role;
        try {
            $this->lastUsed = (is_null($user->lastUsed)) ? null : new Character((string) $user->lastUsed);
        } catch (Exception $e) {
            $this->lastUsed = null;
        }
        try {
            $this->refreshCharIDs();
        } catch (Exception $e) {
            $this->charIDs = null;
        }
    }

    /**
     * @param string $name
     * @param string $pw
     * @return User
     * @throws Exception
     */
    public static function login($name, $pw)
    {
        $query = ['name' => new MongoDB\BSON\Regex("^".$name."$", "i")];
        try {
            $result = queryDocument("pnp.user", $query);
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
     * @param string $name
     * @param string $pw
     * @return bool
     * @throws Exception
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
            $bulk = new MongoDB\Driver\BulkWrite();
            $bulk->insert($new_user);
            try {
                bulkWriteOperation("pnp.user", $bulk);
                return true;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception("User name exists already.");
        }
    }

    /**
     * @param string $name The user name to check if it exists already
     * @return bool User name exists or not
     * @throws Exception
     */
    public static function doesUserNameExist($name)
    {
        $query = ["name" => new MongoDB\BSON\Regex("^".$name."$", "i")];
        try {
            $result = queryDocument("pnp.user", $query);
            return (is_null($result)) ? false : true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $old
     * @param string $new
     * @throws Exception
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
     * @return array
     */
    public function getCharIDs()
    {
        try {
            $this->refreshCharIDs();
        } catch (Exception $e) {
            error_log($e->getMessage());
        } finally {
            return $this->charIDs;
        }
    }

    /**
     * @throws Exception
     */
    public function refreshCharIDs()
    {
        $query = ['user' => new MongoDB\BSON\ObjectId($this->id)];
        $options = [];
        try {
            $chars = queryDocument("pnp.char", $query, $options);
            if (is_null($chars)) {
                $this->charIDs = null;
            } else {
                $this->charIDs = [];
                foreach ($chars as $c) {
                    array_push($this->charIDs, (string) $c->_id);
                }
            }
            $_SESSION["user"] = $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return Character|null
     */
    public function getLastUsed()
    {
        if (is_null($this->lastUsed)) {
            if (!is_null($this->getCharIDs())) {
                try {
                    $this->setLastUsed($this->charIDs[0]);
                } catch (Exception $e) {
                    return $this->lastUsed;
                }
            }
        }
        return $this->lastUsed;
    }

    /**
     * @param string $cid
     * @throws Exception
     */
    public function setLastUsed($cid)
    {
        try {
            $char = new Character($cid);
            $new_char = [
                '$set' => [
                    'lastUsed' => new MongoDB\BSON\ObjectId($char->getId())
                ]
            ];
            $bulk = new MongoDB\Driver\BulkWrite();
            $bulk -> update([ "_id" => new MongoDB\BSON\ObjectId($this->id)], $new_char);
            $char->getId();
            bulkWriteOperation("pnp.user", $bulk);
            $this->lastUsed = $char;
            $_SESSION["user"] = $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return stdClass
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param array $pwd
     * @throws Exception
     */
    public function setPwd($pwd)
    {
        $new_pw = [
            '$set' => [
                'pwd' => $pwd
            ]
        ];
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update(['_id' => new MongoDB\BSON\ObjectId($this->id)], $new_pw);
        try {
            bulkWriteOperation("pnp.user", $bulk);
            $this->pwd = $pwd;
            $_SESSION["user"] = $this;
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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}