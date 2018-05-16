<?php

require_once __DIR__ . "/../bol/person_interest_dao.php";
require_once __DIR__ . "/../bol/interest_dao.php";
require_once __DIR__ . "/../bol/person_dao.php";

class PersonInterestController {
    public function get() {
        $data = $_GET;
        $result = [];

        if (isset($data['personId'])) {
            $personInterests = PersonInterestDao::getInstance()->getInterestsOfPerson($data['personId']);

            foreach ($personInterests as $personInterest) {
                $result[] = InterestDao::getInstance()->getInterestById($personInterest->interestId);
            }
        } else {
            $result = "Error";
        }

        return $result;
    }

    public function deleteInterestFromUesr() {
        $data = $_GET;
        $result = [];

        if (isset($data['interestId']) && isset($data['personId'])) {
            $person = PersonDao::getInstance()->getPersonById($data['personId']);
            
            if (isset($person)) {
                $interest = InterestDao::getInstance()->getInterestById($data['interestId']);
                
                if (isset($interest)) {
                    PersonInterestDao::getInstance()->removeInterestFromPerson($person->id, $interest->id);
                    $result = "Success";
                } else {
                    $result = "Error";
                }
            } else {
                $result = "Error";
            }
        } else {
            $result = "Error";
        }

        return $result;
    }
}