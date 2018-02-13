<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($data['description']))
        {
            $connection     = new SQLite3("../user-store.db");
            $prepared_query = $connection->prepare("INSERT INTO interest(description) VALUES (:description)");
            $prepared_query -> bindValue(":description", $data['description'], SQLITE3_TEXT);
            $prepared_query -> execute();

            $result['adding_interest'] = "Новый интерес был успешно добавлен";
        } else
        {
            $result['error'] = "Пожалуйста укажите нужные поля для новой записи";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);