<?php

if (!isset($_COOKIE['current_session_id'])) {
    $data = $_POST;
    $result = "";

    if (isset($data['username']) && isset($data['password']))
    {
        $user = UserDao::getInstance()->getUserByUsername($data['username']);

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

            $result = "Success";
        } else
        {
            $result = "Error";
        }
    } else
    {
        header("Location: http://localhost/mainPhpProject/itstep_php_project/login.html");
    }
} else 
{
    header("Location: http://localhost/mainPhpProject/itstep_php_project/main.html");
}

return $result;