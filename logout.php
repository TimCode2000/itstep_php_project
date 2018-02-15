<?php
    $result = [];

    if (isset($_COOKIE['current_session_id']))
    {
        $session_id     = $_COOKIE['current_session_id'];

        $connection     = new SQLite3("user-store.db");
        $prepared_query = $connection -> prepare("DELETE FROM sessions WHERE id = :session_id");
        $prepared_query -> bindValue(":session_id", $session_id, SQLITE3_TEXT);
        $prepared_query -> execute();

        setcookie('current_session_id', $session_id, time() - 60 * 60 * 24 * 31, "/");
        $result['logout'] = "Вы вышли из учётной записи";
    } else
    {
        $result['error'] = "Вы не вошли в учётную запись";
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);