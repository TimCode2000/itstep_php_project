<?php

class UserController
{
    public function login()
    {
        $data = $_GET;
        $result = [];

        if (isset($data['username']) && isset($data['password']))
        {
            $user = UserDao::getInstance()->getUserByUsername($data['username']);

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
        } else
        {
            $result['error'] = "Введите данные для входа в учётную запись";
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function logout()
    {

    }
}