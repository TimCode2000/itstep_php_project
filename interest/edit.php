<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($data['description']) && isset($data['new_description']))
        {
            $id = get_interest_id($data['description']);
            $result['edit_interest'] = edit_description($id, $data['new_description']);
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
        $prepared_query -> execute();

        $prepared_query -> close();
        $connection     -> close();
        return "Интерес был успешно изменён";
    }