<?php

require_once "base_dao.php";

class InterestDao extends BaseDao
{
    /**
     * Class instance (singleton pattern implementation)
     * 
     * @var InterestDao
     */

    private static $classInstance;

    /**
     * Constructor
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get instance of class (singleton pattern implementation)
     * 
     * @return InterestDao
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
        return "interest";
    }

    /**
     * Get dto class name
     * 
     * @return string
     */

    public function getDtoClassName()
    {
        return "Interest";
    }

    /**
     * Add interest
     * 
     * @param string
     */

    public function addInterest($description)
    {
        $interest = new Interest();
        $interest->description = $description;

        return $this->save($interest);
    }

    /**
     * Remove interest by description
     * 
     * @param string
     */

    public function removeInterestByDescription($description)
    {
        $query = "description='$description'";

        $this->queryForRemove($query);
    }

    /**
     * Remove interest by id
     * 
     * @param integer
     */

    public function removeInterestById($id)
    {
        $query = "id=$id";

        $this->queryForRemove($query);
    }

    /**
     * Update interest by id
     * 
     * @param string
     * @param integer
     */

    public function updateInterestById($newDescription, $id)
    {
        $query = "UPDATE " . $this->getTableName() . " SET description='" . $newDescription . "' WHERE id=" . $id;
        $this->executeUpdate($query);
    }

    /**
     * Update interest by description
     * 
     * @param string
     * @param string
     */

    public function updateInterestByDescription($newDescription, $description)
    {
        $query = "UPDATE " . $this->getTableName() . " SET description='" . $newDescription . "' WHERE description='" . $description . "'";
        $this->executeUpdate($query);
    }

    /**
     * Get interest by id
     * 
     * @param integer
     */

    public function getInterestById($id)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id=$id";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get interest by description
     * 
     * @param string
     */

    public function getInterestByDescription($description)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE description LIKE '%$description%'";

        return $this->queryForObjectList($query, $this->getDtoClassName());
    }
}