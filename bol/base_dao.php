<?php

require_once "interest.php";
require_once "person_interest.php";
require_once "person.php";
require_once "session.php";
require_once "user.php";

abstract class BaseDao 
{
    /**
     * Connection to database
     * 
     * @var SQLITE3
     */

    protected $sqlite_connection;

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->sqlite_connection = new SQLite3("user-store.db");
    }

    /**
     * Query for list of objects
     * 
     * @param string
     * @param string
     * 
     * @return array
     */

    public function queryForObjectList($query, $dtoClassName)
    {
        $sqliteResult = $this->sqlite_connection->query($query);
        $result = [];

        while ( $row = $sqliteResult->fetchArray(SQLITE3_ASSOC) )
        {
            $entity = new $dtoClassName();

            foreach ($row as $column => $value)
            {
                $entity->$column = $value;
            }

            $result[] = $entity;
        }

        return $result;
    }

    /**
     * Query for one single object
     * 
     * @param string
     * @param string
     * 
     * @return array
     */

    public function queryForObject($query, $dtoClassName)
    {
        $sqliteResult = $this->sqlite_connection->query($query);
        $row = $sqliteResult->fetchArray(SQLITE3_ASSOC);
        $entity = new $dtoClassName();
        
        foreach ($row as $column => $value)
        {
            $entity->$column = $value;
        }

        return $entity;
    }

    /**
     * Save entity in table
     * 
     * @param object
     */

    public function save($entity, $ai = true)
    {
        $columnList = "";
        $valueList = "";

        foreach ($entity as $column => $value)
        {
            if ($column !== "id" || !$ai)
            {
                $columnList .= $column . ", ";
                $valueList .= "'" . $value . "', ";
            }
        }

        $columnList = substr($columnList, 0, strlen($columnList) - 2);
        $valueList = substr($valueList, 0, strlen($valueList) - 2);

        $query = "INSERT INTO " . strtolower(get_class($entity)) . "(" . $columnList . ") VALUES (" . $valueList . ");";

        $newRowInserted = $this->sqlite_connection->exec($query);

        if ( $newRowInserted )
        {
            return $entity;
        }
        else
        {
            return null;
        }
    }

    /**
     * Removing from table by query
     * 
     * @param string
     */

    public function queryForRemove($query)
    {
        $this->sqlite_connection->exec("DELETE FROM " . $this->getTableName() . " WHERE " . $query);
    }

    /**
     * Updates table data
     * 
     * @param string
     */

    public function executeUpdate($query)
    {
        $this->sqlite_connection->exec($query);
    }

    /**
     * Count of rows in table
     * 
     * @param string
     */

    public function count($query = "")
    {
        $condition = "";

        if (!empty($query))
        {
            $condition = " WHERE " . $query;
        }

        return $this->sqlite_connection->query("SELECT COUNT(*) FROM " . $this->getTableName() . $condition)->fetchArray()[0];
    }
}