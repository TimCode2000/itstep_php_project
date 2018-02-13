<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $data = $_GET;
        if (isset($data['id']))
        {
            $person = get_person_by_id($data['id']);

            if ($person)
            {
                $first_name     = (isset($data['first_name']))
                    ? $data['first_name']
                    : $person['first_name'];

                $last_name      = (isset($data['last_name']))
                    ? $data['last_name']
                    : $person['last_name'];

                $phone          = (isset($data['phone']))
                    ? $data['phone']
                    : $person['phone'];

                $active         = (isset($data['active']))
                    ? $data['active']
                    : $person['active'];

                $age            = (isset($data['age']))
                    ? $data['age']
                    : $person['age'];

                $connection     = new SQLite3("../user-store.db");
                $prepared_query = $connection -> prepare("UPDATE person 
                                                        SET first_name = :first_name, last_name = :last_name, phone = :phone, age = :age, active = :active
                                                        WHERE id = :id");

                $prepared_query   -> bindValue(":id", $data['id'], SQLITE3_INTEGER);
                $prepared_query   -> bindValue(":first_name", $first_name, SQLITE3_TEXT);
                $prepared_query   -> bindValue(":last_name", $last_name, SQLITE3_TEXT);
                $prepared_query   -> bindValue(":phone", $phone, SQLITE3_INTEGER);
                $prepared_query   -> bindValue(":age", $age, SQLITE3_INTEGER);
                $prepared_query   -> bindValue(":active", $active, SQLITE3_INTEGER);

                $prepared_query   -> execute();

                $result['result'] = "Данные успешно изменены";
            } else
            {
                $result['error'] = "Вы ввели неверный id";
            }
        } else
        {
            $result['error']  = "Не были указаны данные пользователя";
        }
    } else
    {
        $result['error'] = "Пожалуйста войдите в учётную запись";
    }

    function get_person_by_id($id)
    {
        $connection     = new SQLite3("../user-store.db");

        $prepared_query = $connection -> prepare("SELECT * FROM person WHERE id = :id");
        $prepared_query -> bindValue(":id", $id, SQLITE3_INTEGER);
        $result         = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);

        $prepared_query -> close();
        $connection     -> close();

        return $result;
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);