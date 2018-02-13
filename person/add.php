<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($data['first_name']) &&
            isset($data['last_name']) &&
            isset($data['phone']) &&
            isset($data['age']) &&
            isset($data['active']))
        {
            $connection     = new SQLite3("../user-store.db");
            $prepared_query = $connection -> prepare("INSERT INTO person VALUES(:first_name, :last_name, :phone, :active, :age)");

            $prepared_query -> bindValue(":first_name", $data['first_name'], SQLITE3_TEXT);
            $prepared_query -> bindValue(":last_name", $data['last_name'], SQLITE3_TEXT);
            $prepared_query -> bindValue(":phone", $data['phone'], SQLITE3_NUM);
            $prepared_query -> bindValue(":active", $data['active'], SQLITE3_NUM);
            $prepared_query -> bindValue(":age", $data['age'], SQLITE3_NUM);

            $result['adding_person'] = ($prepared_query -> execute())
                ? "Новый пользователь был успешно добавлен"
                : "Новый пользователь не был добавлен";
        } else
        {
            $result['error'] = "Пожалуйста введите данные необходимые для добавления пользователя";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);