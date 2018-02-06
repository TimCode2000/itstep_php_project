<?php
    $user_loged = false;

    if (isset($_SESSION)) {
        $user_loged = true;
    }

    header("Content-Type: application/json");
    echo json_encode($user_loged);