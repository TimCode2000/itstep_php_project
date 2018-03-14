<?php

class PersonInterestDao extends BaseDao
{
    /**
     * Class instance (singleton pattern implementation)
     * 
     * @var PersonInterestDao
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
     * @return PersonInterestDao
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
        return "person_interest";
    }

    /**
     * Get dto class name
     * 
     * @return string
     */

    public function getDtoClassName()
    {
        return "PersonInterest";
    }

    /**
     * Add interest to person
     * 
     * @param integer
     * @param integer
     */

    public function addInterestToPerson($personId, $interestId)
    {
        $personInterest = new PersonInterest();

        $personInterest->personId = $personId;
        $personInterest->interestId = $interestId;

        return $this->save($personInterest);
    }

    /**
     * Remove interest from person
     * 
     * @param integer
     * @param integer
     */

    public function removeInterestFromPerson($personId, $interestId)
    {
        $query = "personId=$personid AND interestId=$interestId";

        $this->queryForRemove($query);
    }

    /**
     * Get interests list of specified person
     * 
     * @param integer
     */

    public function getInterestsOfPerson($personid)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE personid=$personId";

        return $this->queryForObjectList($query, $this->getDtoClassName());
    }

    /**
     * Get person list with specified interest
     * 
     * @param integer
     */

    public function getPersonsWithInterest($interestId)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE interestId=$interestId";

        return $this->queryForObjectList($query, $this->getDtoClassName());
    }
}