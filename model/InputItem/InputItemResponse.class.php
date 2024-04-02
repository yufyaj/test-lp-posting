<?php

require_once 'InputItem.class.php';

class InputItemResponse
{
    private $errMsg;

    public function __construct()
    {
        $this->errMsg = "";
    }

    public function setErrMsg($value)
    {
        $this->errMsg .= $value . "\n";
    }

    public function getResponse()
    {
        $response = array(
            "Result"       => empty($this->errMsg),
            "ErrorMessage" => $this->errMsg,
        );
        return $response;
    }
}
