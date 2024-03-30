<?php

require_once 'InputItem.php';

class State extends InputItem{
    private function validate($value) {
        if (empty($value)) {
            throw new Exception("都道府県が未選択です");
        }
        return true;
    }
}

?>