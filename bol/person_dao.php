<?php

require_once "base_dao.php";
require_once "interest_dao.php";
require_once "person_interest_dao.php";

class PersonDao extends BaseDao
{
    /**
     * Class instance (singleton pattern implementation)
     * 
     * @var PersonDao
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
     * @return PersonDao
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
        return "person";
    }

    /**
     * Get dto class name
     * 
     * @return string
     */

    public function getDtoClassName()
    {
        return "Person";
    }

    /**
     * Add person
     * 
     * @param string
     * @param string
     * @param integer
     * @param integer
     * @param integer
     */

    public function addPerson($firstName, $lastName, $phone, $active, $age)
    {
        $person = new Person();

        $person->firstName = $firstName;
        $person->lastName = $lastName;  
        $person->phone = $phone;
        $person->active = $active;
        $person->age = $age;
        
        return $this->save($person);
    }

    /**
     * Remove person by id
     * 
     * @param integer
     */

    public function removePersonById($id)
    {
        $query = "id=$id";

        $this->queryForRemove($query);
    }

    /**
     * Remove person by full name
     * 
     * @param string
     */

    public function removePersonByFullName($fullName)
    {
        list($firstName, $lastName) = explode(" ", $fullName);
        $query = "firstName='$firstName' AND lastName='$lastName'";

        $this->queryForRemove($query);
    }

    /**
     * Remove person by phone
     * 
     * @param integer
     */

    public function removePersonByPhone($phone)
    {
        $query = "phone='$phone'";

        $this->queryForRemove($query);
    }

    /**
     * Updates person data
     * 
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param integer
     */

    public function updatePerson($newFirstName, $newLastName, $newPhone, $newActive, $newAge, $personId)
    {
        $newPerson = new Person();
        $newPerson->firstName = $newFirstName;
        $newPerson->lastName = $newLastName;
        $newPerson->phone = $newPhone;
        $newPerson->active = $newActive;
        $newPerson->age = $newAge;

        $setStatement = "";

        foreach ($newPerson as $column => $value)
        {
            if ($column !== "id") 
            {
                $setStatement .= $column . "='" . $value . "',";
            }
        }

        $setStatement = substr($setStatement, 0, strlen($setStatement) - 1);

        $query = "UPDATE " . $this->getTableName() . " SET " . $setStatement . " WHERE id=" . $personId;

        $this->executeUpdate($query);
    }

    /**
     * Get person by id
     * 
     * @var integer
     */

    public function getPersonById($id)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id=$id";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get person by fullName
     * 
     * @param string
     * 
     * @return Person
     */

    public function getPersonByFullName($fullName)
    {
        list($firstName, $lastName) = explode(" ", $fullName);
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE firstName='$firstName' AND lastName='$lastName'";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get person by phone
     * 
     * @param integer
     * 
     * @return Person
     */

    public function getPersonByPhone($phone)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE phone='$phone'";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get persons by interest description
     * 
     * @param string
     * 
     * @return array
     */

    public function getPersonsByInterestDescription($description)
    {
        $interest = InterestDao::getInstance()->getInterestByDescription($description);
        $personInterests = PersonInterestDao::getInstance()->getPersonsWithInterest($interest->id);
        $result = [];

        foreach ($personInterests as $personInterest)
        {
            $result[] = $this->getPersonByid($personInterest->personId);
        }

        return $result;
    }
}