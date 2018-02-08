<?php
    if (isset($_SESSION))
    {
        echo "Вы вошли в систему, перейти на <a href='main.php'>главную страницу</a>";
    } else
    {
        echo "<button class='button' type='button'><a href='../login.html'>Войти</a></button>";
    }