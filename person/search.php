<?php
    $user = [];

    if (isset($_COOKIE["current_session_id"]))
    {
        $connection = new SQLite3("../user-store.db");
        $error_message = "";
        $data = $_GET;

        if (isset($data['name']))
        {
            list($first_name, $last_name) = mb_split(" ", $data['name']);
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
            $prepared_query               = $connection -> prepare(
                "SELECT person.id, person.first_name, person.last_name, person.phone, person.active, person.age FROM person, person_interests, interest 
                  WHERE interest.description = :interest AND
                        interest.id = person_interests.interestId AND
                        person.id = person_interests.personId");

            $prepared_query               -> bindValue(":interest", $data['interest']);
            $sqlite_result                = $prepared_query -> execute();

            while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC))
            {
                $user[] = $row;
            }
        }
    } else
    {
        header("Location: index.php");
    }

    echo json_encode($user);