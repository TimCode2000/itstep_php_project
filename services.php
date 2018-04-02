<?php
    $url = $_GET['q'];
    $separated_url = explode('/', $url);
    $result = [];

    if (count($separated_url) > 2)
    {
        $result['error'] = "Нет такой страницы";
    }
    else
    {
        list($controller_class, $action) = $separated_url;

        if (!file_exists("controllers/" . $controller_class . ".php"))
        {
            $result['error'] = "Нет такой страницы";
        }
        else 
        {
            require("controllers/" . $controller_class . ".php");
            $controller_class = ucfirst($controller_class) . "Controller";
            $controller = new $controller_class();

            if (!method_exists($controller, $action))
            {
                $result['error'] = "Нет такой страницы";
            }
            else
            {
                $result = $controller->$action();
            }
        }
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);