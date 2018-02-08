<?php
    define("USER_IP", $_SERVER['REMOTE_ADDR']);
    $data = $_POST;
    $result = "";

    if (isset($data['do_login']))
    {
        $connection     = new SQLite3("user-store.db");
        $prepared_query = $connection -> prepare("SELECT * FROM user WHERE username = :user");
        $prepared_query -> bindValue(":user", $username, SQLITE3_TEXT);
        $username       = $data['username'];
        $sqlite_result  = $prepared_query -> execute();
        $user           = $sqlite_result -> fetchArray(SQLITE3_ASSOC);

        if ($user)
        {
            if ($data['password'] == $user['password'])
            {
                $current_session = new Session(get_count($connection) + 1, USER_IP);

                $insert_query    = $connection -> prepare("INSERT INTO sessions(uid, time, ip) VALUES(:user_id, ':time', ':ip'");
                $insert_query    -> bindValue(":user_id", $current_session -> user_id, SQLITE3_NUM);
                $insert_query    -> bindValue(":time", $current_session -> time, SQLITE3_TEXT);
                $insert_query    -> bindValue(":ip", $current_session -> ip, SQLITE3_TEXT);
                $insert_query    -> execute();

                $current_session -> set_as_session_object();
                $insert_query    -> close();
                $prepared_query  -> close();
                $connection      -> close();

                $result          = "<center><a href='main.php'>Нажмите здесь</a> чтобы пройти на главную страницу</center>";
            } else
            {
                $result          = "<dir class='error_message'>Данные введены неверно</dir>";
            }
        } else
        {
            $prepared_query -> close();
            $connection     -> close();
            
            $result         = "<dir class='error_message'>Данные введены неверно</dir>";
        }
    } else
    {
        $result = "<dir class='error_message'>Пожалуйста, заполните форму для входа. Для заполнения формы <a href='../login.html'>нажмите сюда</a> </dir>";
    }

    echo $result;

    class Session
    {
        public $user_id, $time, $ip;

        public function __construct($user_id, $ip)
        {
            $this->user_id = $user_id;
            $this->ip = $ip;
            $this->time = time();
        }

        public function set_as_session_object()
        {
            session_start();
            $_SESSION['current_session'] = $this;
        }
    }

    function get_count($connection) {
        $sqlite_result = $connection -> query("SELECT COUNT(id) FROM sessions");
        return (int) $sqlite_result -> fetchArray(SQLITE3_NUM)[0];
    }