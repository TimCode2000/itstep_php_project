<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($_POST['delete_person']) || isset($data['id']))
        {
            $id                       = $data['id'];
            $connection               = new SQLite3("../user-store.db");
            $prepared_query           = $connection -> prepare("DELETE * FROM person WHERE id = :id");
            $prepared_query           -> bindValue(":id", $id, SQLITE3_INTEGER);
            $result['delete_from_p']  = ($prepared_query -> execute())
                ? "Данные из таблицы Person удалены!"
                : "Не удалось удалить данные из таблицы Person";

            $prepared_query           = $connection -> prepare("DELETE * FROM person_interests WHERE personId = :id");
            $prepared_query           -> bindValue(":id", $id, SQLITE3_INTEGER);

            $result['delete_from_pi'] = ($prepared_query -> execute())
                ? "Данные из таблицы Person_Interest удалены!"
                : "Не удалось удалить данные из таблицы Person_Interests";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result);