<?php
    class interest
    {
        private $data;
        private $connection;
        private $result;

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
                if (isset($this -> data['description']))
                {
                    $prepared_query = $this -> connection -> prepare("INSERT INTO interest(description) VALUES (:description)");
                    $prepared_query -> bindValue(":description", $this -> data['description'], SQLITE3_TEXT);
                    $prepared_query -> execute();

                    $this -> result['adding_interest'] = "Новый интерес был успешно добавлен";
                } else
                {
                    $this -> result['error'] = "Пожалуйста введите данные для добавления интереса";
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
                if (isset($this -> data['id']))
                {
                    $this -> result['delete_interest'] = $this -> delete_interest($this -> data['id']);
                } else if (isset($this -> data['description']))
                {
                    $prepared_query = $this -> connection -> prepare("SELECT id FROM interest WHERE description = :description");
                    $prepared_query -> bindValue(":description", $this -> data['description'], SQLITE3_TEXT);
                    $id  = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC) ['id'];

                    $this -> result['delete_interest'] = $this -> delete_interest($id);
                } else
                {
                    $this -> result['error'] = "Пожалуйста введите данные для удаления интереса";
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
                if (isset($this -> data['new_description']))
                {
                    if (isset($this -> data['id']))
                    {
                        $this -> result['edit_interest'] = $this -> edit_interest($this -> data['id'], $this -> data['new_description']);
                    } else if (isset($this -> data['description']))
                    {
                        $prepared_query = $this -> connection -> prepare("SELECT id FROM interest WHERE description = :description");
                        $prepared_query -> bindValue(":description", $this -> data['description'], SQLITE3_TEXT);
                        $id             = $prepared_query -> execute() -> fetchArray(SQLITE3_ASSOC) ['id'];

                        $this -> result['edit_interest'] = $this -> edit_interest($id, $this -> data['new_description']);
                    } else
                    {
                        $this -> result['error'] = "Пожалуйста введите данные для изменения интереса";
                    }
                } else
                {
                    $this -> result['error'] = "Пожалуйста введите данные для измененеия интереса";
                }
            } else
            {
                $this -> result['error'] = "Пожалуйста войдите в учётную запись";
            }
        }

        function search()
        {
            if (isset($_COOKIE['current_session_id']))
            {
                if (isset($this -> data['query']))
                {
                    $prepared_query = $this -> connection -> prepare("SELECT * FROM interest WHERE description LIKE  '%' || :description || '%'");
                    $prepared_query -> bindValue(":description", $this -> data['query'], SQLITE3_TEXT);
                    $sqlite_result  = $prepared_query -> execute();

                    while ($row = $sqlite_result -> fetchArray(SQLITE3_ASSOC)) {
                        $this -> result[] = $row;
                    }
                } else
                {
                    $this -> result['error'] = "Пожалуйста введите данные для поиска интереса";
                }
            } else
            {
                $this -> result['error'] = "Пожалуйста войдите в учётную запись";
            }
        }

        function edit_interest($id, $new_description)
        {
            $prepared_query = $this -> connection -> prepare("UPDATE interest SET description = :description WHERE id = :id");
            $prepared_query -> bindValue(":description", $new_description, SQLITE3_TEXT);
            $prepared_query -> bindValue(":id", $id, SQLITE3_TEXT);
            $prepared_query -> execute();

            return "Интерес был успешно изменён";
        }

        function delete_interest($id)
        {
            $prepared_query = $this -> connection -> prepare("DELETE FROM interest WHERE id = :id");
            $prepared_query -> bindValue(":id", $id, SQLITE3_INTEGER);
            $prepared_query -> execute();

            $prepared_query = $this -> connection -> prepare("DELETE FROM person_interests WHERE interestId = :id");
            $prepared_query -> bindValue(":id", $id, SQLITE3_INTEGER);
            $prepared_query -> execute();

            return "Данные успешно удалены";
        }
    }