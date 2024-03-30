<?php

require_once 'InputItem.php';

class Busu extends InputItem{
    private function validate($value) {
        if (empty($value)) {
            throw new Exception("部数が未選択です");
        }
        if (!is_numeric($value)) {
            throw new Exception("部数が数値ではありません");
        }
        return true;
    }
}

?>