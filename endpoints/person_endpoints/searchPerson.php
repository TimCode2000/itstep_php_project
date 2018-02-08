<?php
    $connection = new SQLite3("../user-store.db");
    $user = [];
    $error_message = "";
    $data = $_GET;

    if (isset($data['name']))
    {
        list($first_name, $last_name) = str_split(" ", $data['name']);
        $prepared_query               = $connection -> prepare("SELECT * FROM person WHERE first_name = :first_name AND last_name = :last_name");
        $prepared_query               -> bindValue(":first_name", $first_name, SQLITE3_TEXT);
        $prepared_query               -> bindValue(":last_name", $last_name, SQLITE3_TEXT);
        $user                         = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);
    } elseif (isset($data['phone']))
    {
        $prepared_query               = $connection -> prepare("SELECT * FROM person WHERE phone = :phone");
        $prepared_query               -> bindValue(":phone", $data['phone'], SQLITE3_ASSOC);
        $user                         = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);
    } elseif (isset($data['interest']))
    {
        $prepared_query               = $connection -> prepare("SELECT personId FROM person_interests WHERE interest.description = :interest AND person_interests.interestId = interest.id");
        $prepared_query               -> bindValue(":interest", $data['interest']);
        $sqlite_result                = $prepared_query -> execute();

        while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC))
        {
            $user[] = $row;
        }
    }

    echo json_encode($user);