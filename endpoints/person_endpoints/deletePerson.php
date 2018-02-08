<?php
    $data = $_GET;
    $result = FALSE;

    if (isset($data['delete_person']))
    {
        $id = $data['id'];
        $connection = new SQLite3("../user-store.db");
        $prepared_query = $connection -> prepare("DELETE * FROM person WHERE id = :id");
        $prepared_query -> bindValue(":id", $id, SQLITE3_INTEGER);
        $result  = $prepared_query -> execute();
    }

    echo $result;