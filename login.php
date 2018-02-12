<?php
    define("USER_IP", $_SERVER['REMOTE_ADDR']);
    $data = $_POST;
    $result = "FALSE";

    if (isset($data['do_login']))
    {
        $connection     = new SQLite3("user-store.db");
        $prepared_query = $connection -> prepare("SELECT * FROM user WHERE username = :user;");
        $username       = $data['username'];
        $prepared_query -> bindValue(":user", $username, SQLITE3_TEXT);
        $sqlite_result  = $prepared_query -> execute();
        $user           = $sqlite_result -> fetchArray(SQLITE3_ASSOC);
        $prepared_query -> close();

        if ($user)
        {
            if ($data['password'] == $user['password'])
            {
                $timestamp       = time();
                $sessions_count  = get_sessions_count($connection);
                $session_id      = hash("ripemd128", ($sessions_count + 1) . $timestamp);
                $user_id         = $sessions_count + 1;
                $current_session = new Session($session_id, $user_id, $timestamp, USER_IP);
                $connection      -> exec("INSERT INTO sessions VALUES('$session_id', $user_id, '$timestamp', '" . USER_IP . "');");
                $current_session -> save_id();
                $result          = "TRUE";
            }
        }
    } else
    {
        header("Location: login.html");
    }

    $connection     -> close();

    echo $result;

    class Session
    {
        public $session_id, $user_id, $time, $ip;

        public function __construct($session_id, $user_id, $time,  $ip)
        {
            $this -> session_id = $session_id;
            $this -> user_id    = $user_id;
            $this -> ip         = $ip;
            $this -> time       = $time;
        }

        public function save_id()
        {
            setcookie("current_session_id", $this -> session_id, time() + 60 * 60 * 24 * 31, "/");
        }
    }

    function get_sessions_count(SQLite3 $connection) {
        $sqlite_result = $connection -> query("SELECT COUNT(id) FROM sessions");
        $result        = $sqlite_result -> fetchArray(SQLITE3_NUM)[0];
        return (int) $result;
    }