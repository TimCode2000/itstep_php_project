<?php

require_once __DIR__ . "/../bol/person_dao.php";

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
            PersonDao::getInstance()->removePersonById($data['id']);
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

    public function search() {
        $data = $_GET;
        $result = [];

        if (isset($data['interest'])) 
        {
            $result = PersonDao::getInstance()->getPersonsByInterestDescription($data['interest']);
        } else if (isset($data['fullName']))
        {
            $result = PersonDao::getInstance()->getPersonByFullName($data['fullName']);
        } else if (isset($data['phone']))
        {
            $result = PersonDao::getInstance()->getPersonByPhone($data['phone']);
        } else
        {
            $result['error'] = "Введите данные для поиска пользователя";
        }

        return $result;
    }

    public function edit()
    {
        $data = $_GET;
        $result = [];

        PersonDao::getInstance()->updatePerson($data['firstName'], $data['lastName'], $data['phone'], $data['active'], $data['age'], $data['id']);
        $result['success'] = "Пользователь успешно обновлён";
        return $result;
    }
}