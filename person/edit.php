<?php
    $data = $_GET;
    $result = "FALSE";

    if (isset($_POST['edit_button']) || isset($data['id']))
    {
        $connection     = new SQLite3("../user-store.db");
        $prepared_query = $connection -> prepare("UPDATE person WHERE id = :id");
        $prepared_query -> bindValue(":id", $data['id'], SQLITE3_NUM);
        $result         = ($prepared_query -> execute()) ? "TRUE" : "FALSE";
    }