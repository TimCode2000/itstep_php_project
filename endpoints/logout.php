<?php
    $result = FALSE;

    if (isset($_SESSION))
    {
        setcookie(session_name(), '', time() - 60 * 60 * 24 * 31, "/");
        session_destroy();
        header("Location: services.php");
        $result = TRUE;
    }

    echo $result;