<?php

require __DIR__ . "/../bol/session_dao.php";
require __DIR__ . "/../bol/user_dao.php";

class UserController
{
    public function login()
    {
        if (!isset($_COOKIE['current_session_id'])) {
            $data = $_GET;
            $result = [];

            if (isset($data['username']) && isset($data['password']))
            {
                $user = UserDao::getInstance()->getUserByUsername($data['username']);

                if ($user)
                {
                    if ($data['password'] == $user->password)
                    {
                        $sessionsDao = SessionDao::getInstance();

                        $timestamp = time();
                        $sessions_count = $sessionsDao->getSessionsCount();
                        $session_id = hash("ripemd128", ($sessions_count + 1) . $timestamp);
                        $user_id = $sessions_count + 1;
                        $user_ip = $_SERVER['REMOTE_ADDR'];
                        $sessionsDao->addSession($session_id, $user_id, $user_ip);
                        setcookie("current_session_id", $session_id, time() + 60 * 60 * 24 * 31, "/");

                        $result['login'] = "Вы вошли в учётную запись";
                    } else
                    {
                        $result['error'] = "Данные для входа в учётную запись введены неверно";
                    }
                } else
                {
                    $result['error'] = "Данные для входа в учётную запись введены неверно";
                }
            } else
            {
                $result['error'] = "Введите данные для входа в учётную запись";
            }
        } else 
        {
            $result['error'] = "Вы уже залогинены";
        }

        return $result;
    }

    public function logout()
    {
        $result = [];

        if (isset($_COOKIE['current_session_id']))
        {
            $session_id = $_COOKIE['current_session_id'];
            $sessionsDao = SessionDao::getInstance();
            $sessionsDao -> removeSessionById($session_id);

            setcookie('current_session_id', $session_id, time() - 60 * 60 * 24 * 31, "/");
            $result['logout'] = "Вы вышли из учётной записи";
        } 
        else
        {
            $result['error'] = "Вы не вошли в учётную запись";
        }

        return $result;
    }
}