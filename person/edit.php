<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;
        if (isset($data['first_name']) && isset($data['last_name']))
        {
            $connection     = new SQLite3("../user-store.db");
            $prepared_query = $connection -> prepare("UPDATE person 
                                                        SET first_name = :first_name, last_name = :last_name, phone = :phone, age = :age, active = :active
                                                        WHERE id = :id");

            $prepared_query   -> bindValue(":id", $data['id'], SQLITE3_NUM);
            $prepared_query   -> bindValue(":first_name", $data['first_name'], SQLITE3_TEXT);
            $prepared_query   -> bindValue(":last_name", $data['last_name'], SQLITE3_TEXT);
            $prepared_query   -> bindValue(":phone", $data['phone'], SQLITE3_INTEGER);
            $prepared_query   -> bindValue(":age", $data['age'], SQLITE3_INTEGER);
            $prepared_query   -> bindValue(":active", $data['active'], SQLITE3_INTEGER);

            $result['result'] = ($prepared_query -> execute()) ? "Данные успешно изменены" : "Не уадлось изменить пользователя";
        } else
        {
            $result['error']  = "Не был указаны данные пользователя";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);