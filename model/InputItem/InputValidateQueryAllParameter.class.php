<?php
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
foreach (glob($pattern) as $filename) {
    require_once $filename;
}

class InputValidateQueryAllParameter
{
    private $type;
    private $state;
    private $city;
    private $busu;
    private $cost;
    private $company;
    private $name;
    private $mail;
    private $privacy;
    private $response;

    public function __construct($get)
    {
        $this->response = new InputItemResponse();

        try {
            $this->type = new Type($get["type"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->state = new State($get["state"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->city = new City($get["city"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->busu = new Busu($get["busu"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->cost = new Cost($get["cost"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->company = new Company($get["company"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->name = new Name($get["name"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->mail = new Mail($get["mail"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
        try {
            $this->privacy = new Privacy($get["privacy"]);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getBusu()
    {
        return $this->busu;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function getPrivacy()
    {
        return $this->privacy;
    }

    public function getResponseJson()
    {
        $response = $this->response->getResponse();
        if ($this->cost != null) {
            $response["Cost"] = $this->cost->getValue();
        }
        return json_encode($response);
    }
}
