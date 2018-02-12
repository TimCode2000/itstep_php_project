<?php
    $result = "FALSE";

    if (isset($_COOKIE['current_session_id']))
    {
        setcookie('current_session_id', $_COOKIE['current_session_id'], time() - 60 * 60 * 24 * 31, "/");
        $result = "TRUE";
    }

    echo $result;