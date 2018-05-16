<?php
    require_once __DIR__ . "/bol/person_dao.php";

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

        if ($data['searchValue'] === "") 
        {
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
            $persons = [];

            if ($data['searchParam'] === "interest") 
            {
                $result = PersonDao::getInstance()->getPersonsByInterestDescription($data['searchValue']);
            } else if ($data['searchParam'] === "fullName")
            {
                $result = PersonDao::getInstance()->getPersonsByFullName($data['searchValue']);
            } else if ($data['searchParam'] === "phone")
            {
                $result = PersonDao::getInstance()->getPersonsByPhone($data['searchValue']);
            }

            for ($i = $offset_multiplier * 20; $i < 20 + $offset_multiplier * 20; $i++) {
                if (isset($result[$i])) {
                    $persons[] = $result[$i];
                }
            }

            $result = $persons;
        }
    } else
    {
        header("Location: http://localhost/mainPhpProject/login.html");
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);