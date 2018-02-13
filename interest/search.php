<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($data['query']))
        {
            $connection     = new SQLite3("../user-store.db");
            $prepared_query = $connection -> prepare("SELECT * FROM interest WHERE description LIKE  '%' || :description || '%'");
            $prepared_query -> bindValue(":description", $data['query'], SQLITE3_TEXT);
            $sqlite_result  = $prepared_query -> execute();

            while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC)) {
                $result[] = $row;
            }
        } else
        {
            $result['error'] = "Пожалуйста добавьте данные для поиска интереса";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);