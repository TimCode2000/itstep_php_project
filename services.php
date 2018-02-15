<?php
    $url = $_GET['url'];
    $separated_url = explode('/', $url);

    if (count($separated_url) == 2)
    {
        $model_name   = $separated_url[0];
        $model_action = (strpos($separated_url[1], ".php")) ? substr($separated_url[1], strpos($separated_url[1], ".php")) : $separated_url[1];

        require "models/$model_name.php";

        $model = new $model_name("user-store.db");
        $model -> $model_action();

        echo $model;
    } else if (count($separated_url) < 2)
    {
        $page = "main.php";

        if (count($separated_url))
        {
            $page = $separated_url[0];
        }

        require "$page";
    } else
    {
        echo "Нет такой страницы";
    }