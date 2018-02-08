<?php
    switch ($_GET['q'])
    {
        case "/login.php":
            require "login.php";
            break;
        case "/logout.php":
            require "logout.php";
            break;
        case "/person/search":
            require "person_endpoints/searchPerson.php";
            break;
        default:
            echo "Нихуя";
            break;
    }