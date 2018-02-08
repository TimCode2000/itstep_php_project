<?php
    $connection     = new SQLite3("user-store.db");
    $sqlite_result  = $connection -> query("SELECT * FROM person");
    $result = [];

    while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC))
    {
        $result[] = [$row['id'], $row['first_name'], $row['last_name'], $row['phone'], $row['active'], $row['age']];
    }

    echo json_encode($result);