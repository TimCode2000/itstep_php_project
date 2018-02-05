<?php
    require "libs/rb.php";

    class MyORM extends SQLite3
    {
        public function __construct($filename)
        {
            $this->open($filename);
        }

        function find($table_name, $condition, $variables)
        {
            while ($position = strpos($condition, '?'))
            {
                $condition = substr($condition, 0, $position - 1) .
                    "'" . array_shift($variables) . "'" .
                    substr($condition, $position + 1);

                array_splice($variables, $position, 1);
            }

            echo $condition;

            $sqlite_result = $this->query("SELECT * FROM $table_name WHERE $condition");
            return $sqlite_result->fetchArray();
        }
    }

    $x = new MyORM("D:\phpProject\itstep_php_project\user-store.db");
    $result = $x->find("user", "username = ?", array('Clinton'));
    print_r($result);