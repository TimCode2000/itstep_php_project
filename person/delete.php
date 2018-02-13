<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($_POST['delete_person']) || isset($data['id']))
        {
            $id                       = $data['id'];
            $connection               = new SQLite3("../user-store.db");
            $prepared_query           = $connection -> prepare("DELETE FROM person WHERE id = :id");
            $prepared_query           -> bindValue(":id", $id, SQLITE3_INTEGER);
            $prepared_query           -> execute();

            $prepared_query           = $connection -> prepare("DELETE FROM person_interests WHERE personId = :id");
            $prepared_query           -> bindValue(":id", $id, SQLITE3_INTEGER);
            $prepared_query           -> execute();

            $result['delete_interest'] = "Данные успешно удалены";
        } else
        {
            $result['error'] = "Не были указаны данные пользователя";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);