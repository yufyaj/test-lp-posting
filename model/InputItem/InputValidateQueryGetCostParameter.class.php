<?php
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
foreach (glob($pattern) as $filename) {
    require_once $filename;
}

class InputValidateQueryGetCostParameter
{
    private $type;
    private $state;
    private $city;
    private $busu;
    private $cost;
    private $response;

    public function __construct($get)
    {
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

        $this->response = new InputItemResponse();
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

    /* ここのセッターはどうする？ */
    public function setCost($cost)
    {
        try {
            $this->cost = new Cost($cost);
        } catch (Exception $err) {
            $this->response->setErrMsg($err->getMessage());
        }
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
