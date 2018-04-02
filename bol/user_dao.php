<?php

require "user.php";

class UserDao extends BaseDao
{
    /**
     * Class instance (singleton pattern implementation)
     * 
     * @var UserDao
     */

    private static $classInstance;

    /**
     * Constructor
     */

    private function __construct()
    {
        parent::__construct();
    }

    /**
     * Get instance of class
     * 
     * @return UserDao
     */

    public static function getInstance()
    {
        if (self::$classInstance === null)
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * Get table name
     * 
     * @return string
     */

    public function getTableName()
    {
        return "user";
    }

    /**
     * Get dto class name
     * 
     * @return string
     */

    public function getDtoClassName()
    {
        return "User";
    }

    /**
     * Add new user
     * 
     * @param string
     * @param string
     * 
     * @return User
     */

    public function addUser($username, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->password = $password;

        return $this->save($user);
    }

    /**
     * Remove user by username
     * 
     * @param string 
     */

    public function removeUserByUsername($username)
    {
        $query = "username=$username";

        $this->queryForRemove($query);
    }

    /**
     * Remove user by id
     * 
     * @param integer
     */

    public function removeUserById($id)
    {
        $query = "id=$id";

        $this->queryForRemove($query);
    }

    /**
     * Get user by username
     * 
     * @param string
     */

    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE username='$username'";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get user by id
     * 
     * @param integer
     */

    public function getUserById($id)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE username='$username'";

        return $this->queryForObject($query, $this->getDtoClassName);
    }
}