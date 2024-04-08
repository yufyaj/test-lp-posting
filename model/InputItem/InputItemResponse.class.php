<?php

class InputItemResponse
{
    /* 配列にする */
    private $errMsg;

    public function __construct()
    {
        $this->errMsg = [];
    }

    public function setErrMsg($value)
    {
        array_push($this->errMsg, $value);
    }

    public function getResponse()
    {
        $response = array(
            "Result"       => count($this->errMsg) == 0,
            "ErrorMessage" => implode('\n', $this->errMsg),
        );
        return $response;
    }
}
