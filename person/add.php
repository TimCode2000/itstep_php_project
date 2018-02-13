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
            $prepared_query = $connection -> prepare("INSERT INTO person(first_name, last_name, phone, active, age) VALUES(:first_name, :last_name, :phone, :active, :age)");

            $prepared_query -> bindValue(":first_name", $data['first_name'], SQLITE3_TEXT);
            $prepared_query -> bindValue(":last_name", $data['last_name'], SQLITE3_TEXT);
            $prepared_query -> bindValue(":phone", $data['phone'], SQLITE3_INTEGER);
            $prepared_query -> bindValue(":active", $data['active'], SQLITE3_INTEGER);
            $prepared_query -> bindValue(":age", $data['age'], SQLITE3_INTEGER);

            $result['adding_person'] = "Новый пользователь был успешно добавлен";
        } else
        {
            $result['error'] = "Не были указаны данные пользователя";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);