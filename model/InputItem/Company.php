<?php

require_once 'InputItem.php';

class Company extends InputItem{
    private function validate($value) {
        if (empty($value)) {
            throw new Exception("会社名が未入力です");
        }
        return true;
    }
}

?>