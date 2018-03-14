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

        if (!file_exists(__DIR__ . "/controllers/" . $controller))
        {
            $result['error'] = "Нет такой страницы";
        }
        else 
        {
            require($controller_class . "_controller");
            $controller_class = ucfirst($controller_class) . "Controller";
            $controller = new $controller_class ();

            if (!method_exists($controller, $action))
            {
                $result['error'] = "Нет такой страницы";
            }
            else
            {
                $result['success'] = $controller->$action();
            }
        }
    }

    echo $result;