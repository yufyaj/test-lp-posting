<?php

require_once 'InputItem.php';

class Name extends InputItem{
    private function validate($value) {
        if (empty($value)) {
            throw new Exception("担当者名が未入力です");
        }
        return true;
    }
}

?>