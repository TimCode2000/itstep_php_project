<?php
    $data = $_POST;

    if (isset($data['do_login'])) {
       $connection = new SQLite3("../user-store.db");
       $prepared_query = $connection->prepare("SELECT * FROM user WHERE username = ':username' AND password = ':password'");
       $prepared_query->bindValue(":username", $data['username'], SQLITE3_TEXT);
       $prepared_query->bindValue(":password", $data['password'], SQLITE3_TEXT);
       $sqlite_result = $prepared_query->execute();
       $user = $sqlite_result->fetchArray();

        if ($user && count($user) < 2) {
          
        }
    }

    class Session 
    {
        public $id, $uid, $time, $ip;

        function __construct($id, $uid, $time, $ip) 
        {
            $this->id = $id;
            $this->uid = $uid;
            $this->time = $time;
            $this->ip = $ip;
        }

        function __toString() {
            return "($id, $uid, $time, $ip)";
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }