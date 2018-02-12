<?php
    switch ($_GET['q'])
    {
        case "/login":
            require "login.php";
            break;
        case "/logout":
            require "logout.php";
            break;
        case "/person/search":
            require "person/search.php";
            break;
        case "/person/delete":
            require "person/delete.php";
            break;
        case "/main":
            require "main.php";
            break;
        default:
            echo "No endpoint found";
            break;
    }