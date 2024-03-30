<?php

require_once 'InputItem.php';

class InputItemResponse{
    private $errMsg;

    public function __construct() {
        $this->errMsg = "";
    }

    public function setErrMsg($value) {
        $this->errMsg = $value;
    }

    public function getResponseJson() {
        $response = array("Result"       => empty($this->errMsg),
                          "ErrorMessage" => $this->errMsg,);
        return json_encode($response);
    }
}

?>