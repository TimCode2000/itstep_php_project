<?php
    $data = $_GET;
    $result = [];

    if (isset($data['username']) && isset($data['password']))
    {
        $connection     = new SQLite3("user-store.db");
        $prepared_query = $connection -> prepare("SELECT * FROM user WHERE username = :user");
        $prepared_query -> bindValue(":user", $data['username'], SQLITE3_TEXT);
        $user           = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);

        if ($user)
        {
            if ($data['password'] == $user['password'])
            {
                $timestamp       = time();
                $sessions_count  = get_sessions_count($connection);
                $session_id      = hash("ripemd128", ($sessions_count + 1) . $timestamp);
                $user_id         = $sessions_count + 1;
                $current_session = new Session($session_id, $user_id, $timestamp, $_SERVER['REMOTE_ADDR']);

                $connection      -> exec("INSERT INTO sessions VALUES('$session_id', $user_id, '$timestamp', '" . $_SERVER['REMOTE_ADDR'] . "');");
                $current_session -> save_id();

                $result['login'] = "Вы вошли в учётную запись";
            } else
            {
                $result['error'] = "Данные для входа в учётную запись введены неверно";
            }
        } else
        {
            $result['error'] = "Данные для входа в учётную запись введены неверно";
        }

        $prepared_query -> close();
        $connection     -> close();
    } else
    {
        $result['error'] = "Введите данные для входа в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

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
        return $result;
    }