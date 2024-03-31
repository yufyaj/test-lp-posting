<?php

require_once 'InputItem.php';

class City extends InputItem{
    #[\Override]
    protected function validate($value) {
        if (empty($value)) {
            throw new Exception("市区町村が未選択です");
        }
        return true;
    }
}

?>