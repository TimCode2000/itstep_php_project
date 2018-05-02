<?php

require_once __DIR__ . "/../bol/person_dao.php";

class PersonController {
    public function add() {
        $data = $_GET;
        $result = [];

        if (isset($data['firstName']) && isset($data['lastName']) && isset($data['phone']) && isset($data['active']) && isset($data['age'])) 
        {
            PersonDao::getInstance()->addPerson($data['firstName'], $data['lastName'], $data['phone'], $data['active'], $data['age']);

            $result = "Пользователь добавлен успешно";
        } else
        {
            $result = "Введите данные для создания пользователя";
        }

        return $result;
    }

    public function delete() {
        $data = $_GET;
        $result = [];

        if (isset($data['id']))
        {
            PersonDao::getInstance()->removePersonById($data['id']);
            $result = "Пользователь удалён успешно";
        }
        else if (isset($data['firstName']) && isset($data['lastName']))
        {
            PersonDao::getInstance()->removePersonByFullName($data['firstName'] + " " + $data['lastName']);
            $result = "Пользователь удалён успешно";
        }
        else
        {
            $result = "Введите данные для удаления пользователя";
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
            $result = PersonDao::getInstance()->getPersonsByFullName($data['fullName']);
        } else if (isset($data['phone']))
        {
            $result = PersonDao::getInstance()->getPersonsByPhone($data['phone']);
        } else if (isset($data['id'])) {
            $result[] = PersonDao::getInstance()->getPersonById($data['id']);
        } else
        {
            $result = "Login";
        }

        return $result;
    }

    public function edit()
    {
        $data = $_GET;
        $result = [];

        PersonDao::getInstance()->updatePerson($data['firstName'], $data['lastName'], $data['phone'], $data['active'], $data['age'], $data['id']);
        $result = "Success";
        return $result;
    }

    public function addInterest() {
        $data = $_GET;
        $result = [];

        if (isset($data['description'])) {
            InterestDao::getInstance()->addInterest($data['description']);
            $interest = InterestDao::getInstance()->getInterestByDescription($data['description'])[0];
            PersonInterestDao::getInstance()->addInterestToPerson($data['personId'], $interest->id);
            $result = "Success";
        } else {
            $result = "Error";
        }

        return $result;
    }

    public function getPageCount() {
        $persons = PersonDao::getInstance()->getPersons();
        return floor(count($persons) / 20);
    }
}