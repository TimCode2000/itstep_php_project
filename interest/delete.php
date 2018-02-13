<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {

        if (isset($data['id']))
        {
            $result = delete_by_id($data['id']);
        } else if (isset($data['description']))
        {
            $id     = select_by_id();
            $result = delete_by_id($id);
        } else
        {
            $result['error'] = "Пожалуйста введите данные для удаления интереса";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

    function delete_by_id($id)
    {
        $data = $_GET;
        $connection              = new SQLite3("../user-store.db");
        $prepared_query          = $connection -> prepare("DELETE * FROM interest WHERE id = :id");
        $prepared_query          -> bindValue(":id", $id, SQLITE3_INTEGER);
        $result['delete_from_i'] = ($prepared_query -> execute())
            ? "Данные успешно удалены из таблицы Interest"
            : "Данные не были удалены из таблицы Interest";

        $prepared_query          = $connection -> prepare("DELETE * FROM person_interests WHERE interestId = :id");
        $prepared_query          -> bindValue(":id", $id, SQLITE3_INTEGER);
        $result['delete_from_pi'] = ($prepared_query -> execute())
            ? "Данные успешно удалены из таблицы Person_Interests"
            : "Данные не были удалены из таблицы Person_Interests";

        $prepared_query -> close();
        $connection -> close();

        return $result;
    }

    function select_by_id()
    {
        $data = $_GET;
        $connection     = new SQLite3("../user-store.db");
        $prepared_query = $connection -> prepare("SELECT id FROM interest WHERE description = :description");
        $prepared_query -> bindValue(":description", $data['description'], SQLITE3_TEXT);
        $result         = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC) ['id'];

        return $result;
    }