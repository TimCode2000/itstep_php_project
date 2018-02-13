<?php
    switch ($_GET['q'])
    {
        case "/login.php":
            require "login.php";
            break;
        case "/logout.php":
            require "logout.php";
            break;
        case "/person/search.php":
            require "person/search.php";
            break;
        case "/person/delete.php":
            require "person/delete.php";
            break;
        case "/main.php":
            require "main.php";
            break;
        default:
            echo "No endpoint found";
            break;
    }