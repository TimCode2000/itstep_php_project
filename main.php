<?php
    $result = [];
    $data = $_GET;

    if (isset($_COOKIE['current_session_id']))
    {
        $connection     = new SQLite3("user-store.db");
        $page = 1;

        if (isset($data['page']))
        {
            $page = $data['page'];
        }

        $sqlite_result  = $connection -> query("SELECT * FROM person LIMIT 20 OFFSET " . $page * 20);

        while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC))
        {
            $result[] = [$row['id'], $row['first_name'], $row['last_name'], $row['phone'], $row['active'], $row['age']];
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    echo json_encode($result);