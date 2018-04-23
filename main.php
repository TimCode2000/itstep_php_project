<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;
        $connection     = new SQLite3("user-store.db");
        $offset_multiplier = 0;

        if (isset($data['page']))
        {
            $offset_multiplier = $data['page'] - 1;
        }

        $prepared_query = $connection -> prepare("SELECT * FROM person LIMIT 20 OFFSET :offset_multiplier * 20");
        $prepared_query -> bindValue(":offset_multiplier", $offset_multiplier, SQLITE3_INTEGER);
        $sqlite_result  = $prepared_query -> execute();
        $persons = [];

        while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC))
        {
            $persons[] = $row;
        }

        $result = $persons;
    } else
    {
        $result = "Log in";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);