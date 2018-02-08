<?php
    if (isset($_SESSION))
    {
        setcookie(session_name(), '', time() - 60 * 60 * 24 * 31, "/");
        session_destroy();
        header("Location: index.php");
    } else
    {
        echo "Вы не залогинились. Чтобы залогиниться <a href='../login.html'>нажмите сюда</a>";
    }