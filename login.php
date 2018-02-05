<?php
    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    require 'db_connect.php';
    $data = $_POST;

    if (isset($data['do_login'])) {
        $user = R::findOne('user', 'username = ? AND password = ?', array($data['username'], $data['password']));

        if ($user) {
            $session = R::dispense('sessions');
            $session->uid = R::count('sessions') + 1;
            $session->time = time();
            $session->ip = get_client_ip();
            var_dump($session);
            R::store($session);
            session_start();
            $_SESSION['current_session'] = $session;
            header('Location: main.php');
        } else {
            echo "<div id='login_error'>Данные введёны неверно</div>";
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
    <link rel="stylesheet" href="design/style.css">
</head>
<body>
    <form class="form_blank" action="login.php" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%)" method="POST">
        <fieldset>
            Логин: <br>
            <input type="text" name="username" class="input_form" placeholder="Введите логин..." style="width: 200px"><br>
            Пароль: <br>
            <input type="password" name="password" class="input_form" placeholder="Введите пароль..." style="width: 200px"><br>
            <button class="button" name="do_login" type="submit" style="margin-top: 20px" value="Рут">Войти</button>
        </fieldset>
    </form>
</body>
</html>