<?php

require_once __DIR__ . "/../bol/person_interest_dao.php";
require_once __DIR__ . "/../bol/interest_dao.php";

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
}