<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($data['new_description']))
        {
            if (isset($data['description']))
            {
                $id = get_interest_id($data['description']);
                $result['edit_interest'] = edit_description($id, $data['new_description']);
            } else if (isset($data['id']))
            {
                $result['edit_interest'] = edit_description($data['id'], $data['new_description']);
            } else
            {
                $result['error'] = "Пожалуйста введите данные для изменения интереса";
            }
        } else
        {
            $result['error'] = "Пожалуйста введите данные для измененеия интереса";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

    function get_interest_id($description) {
        $connection     = new SQLite3("../user-store.db");
        $prepared_query = $connection -> prepare("SELECT id FROM interest WHERE description = :description");

        $prepared_query -> bindValue(":description", $description, SQLITE3_TEXT);
        $sqlite_result = $prepared_query -> execute();

        return $sqlite_result -> fetchArray(SQLITE3_ASSOC) ['id'];
    }

    function edit_description($id, $new_description)
    {
        $connection     = new SQLite3("../user-store.db");
        $prepared_query = $connection -> prepare("UPDATE interest SET description = :description WHERE id = :id");

        $prepared_query -> bindValue(":description", $new_description, SQLITE3_TEXT);
        $prepared_query -> bindValue(":id", $id, SQLITE3_TEXT);

        $sqlite_result = $prepared_query -> execute();
        $query_result = $sqlite_result -> fetchArray();

        return ($query_result) ? "Интерес был успешно изменён" : "Интерес не был изменён";
    }