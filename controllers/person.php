<?php

require_once __DIR__ . "/../bol/person_dao.php";
require_once __DIR__ . "/../bol/person_interest_dao.php";

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
            $person = PersonDao::getInstance()->getPersonById($data['id']);
            PersonDao::getInstance()->removePersonById($data['id']);
            PersonInterestDao::getInstance()->removePersonsInterests($person->id);
            $result = "Пользователь удалён успешно";
        }
        else if (isset($data['firstName']) && isset($data['lastName']))
        {
            $person = PersonDao::getInstance()->getPersonsByFullName($data['firstName'] + " " + $data['lastName']);
            PersonDao::getInstance()->removePersonByFullName($data['firstName'] + " " + $data['lastName']);
            PersonInterestDao::getInstance()->removePersonsInterests($person->id);
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
            $result = "http://localhost/mainPhpProject/login.html";
        }

        return $result;
    }

    public function edit()
    {
        $data = $_GET;
        $result = [];

        if (isset($data['firstName']) && isset($data['lastName']) && isset($data['phone']) && isset($data['active']) && isset($data['age']) && isset($data['id'])) {
            PersonDao::getInstance()->updatePerson($data['firstName'], $data['lastName'], $data['phone'], $data['active'], $data['age'], $data['id']);
            return "Success";
        } else {
            return "Error";
        }
    }

    public function addInterest() {
        $data = $_GET;
        $result = [];

        if (isset($data['description'])) {
            $interestDao = InterestDao::getInstance();

            if ($interestDao->getInterestByDescription($data['description'])->id === null) {
                $interestDao->addInterest($data['description']);
            }
            
            $interest = $interestDao->getInterestByDescription($data['description']);
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