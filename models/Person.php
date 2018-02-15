<?php
    class Person
    {
        public $data;
        public $connection;
        public $result;

        function __construct($database_location)
        {
            $this -> data       = $_GET;
            $this -> connection = new SQLite3($database_location);
            $this -> result     = [];
        }

        function __toString()
        {
            return json_encode($this -> result, JSON_UNESCAPED_UNICODE);
        }

        function add()
        {
            if (isset($_COOKIE['current_session_id']))
            {
                if (isset($this -> data['first_name']) &&
                    isset($this -> data['last_name']) &&
                    isset($this -> data['phone']) &&
                    isset($this -> data['age']) &&
                    isset($this -> data['active']))
                {
                    $prepared_query = $this -> connection -> prepare(
                        "INSERT INTO person(first_name, last_name, phone, active, age) 
                              VALUES(:first_name, :last_name, :phone, :active, :age)");

                    $prepared_query -> bindValue(":first_name", $this -> data['first_name'], SQLITE3_TEXT);
                    $prepared_query -> bindValue(":last_name", $this -> data['last_name'], SQLITE3_TEXT);
                    $prepared_query -> bindValue(":phone", $this -> data['phone'], SQLITE3_INTEGER);
                    $prepared_query -> bindValue(":active", $this -> data['active'], SQLITE3_INTEGER);
                    $prepared_query -> bindValue(":age", $this -> data['age'], SQLITE3_INTEGER);

                    $this -> result['adding_person'] = "Новый пользователь был успешно добавлен";
                } else
                {
                    $this -> result['error'] = "Пожалуйста введите данные для добавления пользователя";
                }
            } else
            {
                $this -> result['error'] = "Пожалуйста войдите в учётную запись";
            }
        }

        function delete()
        {
            if (isset($_COOKIE['current_session_id']))
            {
                if (isset($_POST['delete_person']) || isset($this -> data['id']))
                {
                    $id                       = $this -> data['id'];
                    $prepared_query           = $this -> connection -> prepare("DELETE FROM person WHERE id = :id");
                    $prepared_query           -> bindValue(":id", $id, SQLITE3_INTEGER);
                    $prepared_query           -> execute();

                    $prepared_query           = $this -> connection -> prepare("DELETE FROM person_interests WHERE personId = :id");
                    $prepared_query           -> bindValue(":id", $id, SQLITE3_INTEGER);
                    $prepared_query           -> execute();

                    $this -> result['delete_interest'] = "Данные успешно удалены";
                } else
                {
                    $this -> result['error'] = "Пожалуйста введите данные для удаления пользователя";
                }
            } else
            {
                $this -> result['error'] = "Пожалуйста войдите в учётную запись";
            }
        }

        function edit()
        {
            if (isset($_COOKIE['current_session_id']))
            {
                if (isset($this -> data['id']))
                {
                    $person = $this -> get_person_by_id($this -> data['id']);

                    if ($person)
                    {
                        $first_name     = (isset($this -> data['first_name']))
                            ? $this -> data['first_name']
                            : $person['first_name'];

                        $last_name      = (isset($data['last_name']))
                            ? $this -> data['last_name']
                            : $person['last_name'];

                        $phone          = (isset($data['phone']))
                            ? $this -> data['phone']
                            : $person['phone'];

                        $active         = (isset($data['active']))
                            ? $this -> data['active']
                            : $person['active'];

                        $age            = (isset($data['age']))
                            ? $this -> data['age']
                            : $person['age'];

                        $prepared_query = $this -> connection -> prepare("UPDATE person 
                                                        SET first_name = :first_name, last_name = :last_name, phone = :phone, age = :age, active = :active
                                                        WHERE id = :id");

                        $prepared_query   -> bindValue(":id", $this -> data['id'], SQLITE3_INTEGER);
                        $prepared_query   -> bindValue(":first_name", $first_name, SQLITE3_TEXT);
                        $prepared_query   -> bindValue(":last_name", $last_name, SQLITE3_TEXT);
                        $prepared_query   -> bindValue(":phone", $phone, SQLITE3_INTEGER);
                        $prepared_query   -> bindValue(":age", $age, SQLITE3_INTEGER);
                        $prepared_query   -> bindValue(":active", $active, SQLITE3_INTEGER);

                        $prepared_query   -> execute();

                        $this -> result['edit_person'] = "Данные успешно изменены";
                    } else
                    {
                        $this -> result['error'] = "Вы ввели неверный id";
                    }
                } else
                {
                    $this -> result['error']  = "Пожалуйста введите данные для изменения пользователя";
                }
            } else
            {
                $this -> result['error'] = "Пожалуйста войдите в учётную запись";
            }
        }

        function search()
        {
            if (isset($_COOKIE["current_session_id"]))
            {
                if (isset($this -> data['name']))
                {
                    list($first_name, $last_name) = mb_split(" ", $this -> data['name']);
                    $prepared_query               = $this -> connection -> prepare("SELECT * FROM person WHERE first_name = :first_name AND last_name = :last_name");
                    $prepared_query               -> bindValue(":first_name", $first_name, SQLITE3_TEXT);
                    $prepared_query               -> bindValue(":last_name", $last_name, SQLITE3_TEXT);
                    $this -> result[]             = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);
                } elseif (isset($this -> data['phone']))
                {
                    $prepared_query               = $this -> connection -> prepare("SELECT * FROM person WHERE phone = :phone");
                    $prepared_query               -> bindValue(":phone", $this -> data['phone'], SQLITE3_ASSOC);
                    $this -> result[]             = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);
                } elseif (isset($this -> data['interest']))
                {
                    $prepared_query               = $this -> connection -> prepare(
                        "SELECT person.id, person.first_name, person.last_name, person.phone, person.active, person.age 
                              FROM person, person_interests, interest 
                              WHERE interest.description = :interest AND
                                    interest.id = person_interests.interestId AND
                                    person.id = person_interests.personId");

                    $prepared_query               -> bindValue(":interest", $this -> data['interest']);
                    $sqlite_result                = $prepared_query -> execute();

                    while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC))
                    {
                        $this -> result[] = $row;
                    }
                }
            } else
            {
                $this -> result['error'] = "Пожалуйста войдите в учётную запись";
            }
        }

        function get_person_by_id($id)
        {
            $prepared_query = $this -> connection -> prepare("SELECT * FROM person WHERE id = :id");
            $prepared_query -> bindValue(":id", $id, SQLITE3_INTEGER);

            return $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC);
        }
    }