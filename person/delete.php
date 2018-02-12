<?php
    $result = "FALSE";

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;

        if (isset($_POST['delete_person']) || isset($data['id']))
        {
            $id = $data['id'];
            $connection = new SQLite3("../user-store.db");
            $prepared_query = $connection -> prepare("DELETE * FROM person WHERE id = :id");
            $prepared_query -> bindValue(":id", $id, SQLITE3_INTEGER);
            $result  = ($prepared_query -> execute()) ? "TRUE" : "FALSE";
        }
    } else
    {
        echo "Пожалуйста, войдите в учётную запись";
    }

    echo $result;