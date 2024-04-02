<?php
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
foreach (glob($pattern) as $filename) {
    require_once $filename;
}

class InputItem
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

    public function __construct()
    {
        $this->response = new InputItemResponse();
    }

    public function setType($type)
    {
        try {
            $this->type = new Type($type);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setState($state)
    {
        try {
            $this->state = new State($state);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCity($city)
    {
        try {
            $this->city = new City($city);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setBusu($busu)
    {
        try {
            $this->busu = new Busu($busu);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getBusu()
    {
        return $this->busu;
    }

    public function setCost($cost)
    {
        try {
            $this->cost = new Cost($cost);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCompany($company)
    {
        try {
            $this->company = new Company($company);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setName($name)
    {
        try {
            $this->name = new Name($name);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMail($mail)
    {
        try {
            $this->mail = new Mail($mail);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function setPrivacy($privacy)
    {
        try {
            $this->privacy = new Privacy($privacy);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
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
