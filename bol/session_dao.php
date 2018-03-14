<?php

class SessionDao extends BaseDao
{
    /**
     * Class instance (singleton pattern implementation)
     * 
     * @var SessionDao
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
     * @return SessionDao
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
        return "session";
    }

    /**
     * Get dto class name
     * 
     * @return string
     */

    public function getDtoClassName()
    {
        return "Session";
    }

    /**
     * Add session
     * 
     * @param integer
     * @param string
     */

    public function addSession($id, $uid, $ip)
    {
        $session = new Session();

        $session->id = $id;
        $session->uid = $uid;
        $session->time = time();
        $session->ip = $ip;

        return $this->save($session, false);
    }

    /**
     * Remove session by sessionId
     * 
     * @param string
     */

    public function removeSessionById($id)
    {
        $query = "id=$id";

        $this->queryForRemove($query);
    }

    /**
     * Get session by id
     * 
     * @param string
     */

    public function getSessionById($id)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id=$id";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get sessions by uid
     * 
     * @param integer
     */
    
    public function getSessionsByUid($uid)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE uid=$uid";

        return $this->queryForObjectlist($query, $this->getDtoClassName());
    }

    /**
     * Get session by ip
     * 
     * @param string
     */

    public function getSessionByIp($ip)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE ip=$ip";

        return $this->queryForObject($query, $this->getDtoClassName());
    }

    /**
     * Get count of all sessions
     */

    public function getSessionsCount()
    {
        return $this->count();
    }
}