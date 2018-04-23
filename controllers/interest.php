<?php

require __DIR__ . "/../bol/interest_dao.php";

class InterestController
{
    public function add()
    {
        $data = $_GET;
        $result = [];

        if (isset($data['description'])) 
        {
            InterestDao::getInstance()->addInterest($data['description']);
            $result['success'] = "Интерес добавлен успешно";
        } else
        {
            $result['error'] = "Введите данные для добавления интереса";
        }

        return $result;
    }

    public function delete()
    {
        $data = $_GET;
        $result = [];

        if (isset($data['id']))
        {
            InterestDao::getInstance()->removeInterestById($data['id']);
            $result['success'] = "Интерес удалён успешно";
        } else if (isset($data['description']))
        {
            InterestDao::getInstance()->removeInterestByDescription($data['description']);
            $result['success'] = "Интерес удалён успешно";
        } else
        {
            $result['error'] = "Введите данные для удаления интереса";
        }

        return $result;
    }

    public function search()
    {
        $data = $_GET;
        $result = [];

        if (isset($data['id']))
        {
            $result = InterestDao::getInstance()->getInterestById($data['id']);
        } else if (isset($data['description']))
        {
            $result = InterestDao::getInstance()->getInterestByDescription($data['description']);
        } else
        {
            $result['error'] = "Введите данные для поиска интереса";
        }

        return $result;
    }

    public function edit()
    {
        $data = $_GET;
        $result = [];

        if (isset($data['newDescription']))
        {
            if (isset($data['description']))
            {
                InterestDao::getInstance()->updateInterestByDescription($data['newDescription'], $data['description']);
                $result['success'] = "Интерес изменён успешно";
            } else if (isset($data['id']))
            {
                InterestDao::getInstance()->updateInterestById($data['newDescription'], $data['id']);
                $result['success'] = "Интерес изменён успешно";
            } else
            {
                $result['error'] = "Введите данные для изменения интереса";
            }
        } else 
        {
            $result['error'] = "Введите данные для изменения интереса";
        }

        return $result;
    }
}