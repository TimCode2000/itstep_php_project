<?php

require __DIR__ . "/../bol/person_dao.php";

class PersonController {
    public function add() {
        $data = $_GET;
        $result = [];

        if (isset($data['firstName']) && isset($data['lastName']) && isset($data['phone']) && isset($data['active']) && isset($data['age'])) 
        {
            PersonDao::getInstance()->addPerson($data['firstName'], $data['lastName'], $data['phone'], $data['active'], $data['age']);

            $result['success'] = "Пользователь добавлен успешно";
        } else
        {
            $result['error'] = "Введите данные для создания пользователя";
        }

        return $result;
    }

    public function delete() {
        $data = $_GET;
        $result = [];

        if (isset($data['id']))
        {
            PersonDao::getInstance()->removePersonById($id);
            $result['success'] = "Пользователь удалён успешно";
        }
        else if (isset($data['firstName']) && isset($data['lastName']))
        {
            PersonDao::getInstance()->removePersonByFullName($data['firstName'] + " " + $data['lastName']);
            $result['success'] = "Пользователь удалён успешно";
        }
        else
        {
            $result['error'] = "Введите данные для удаления пользователя";
        }

        return $result;
    }
}