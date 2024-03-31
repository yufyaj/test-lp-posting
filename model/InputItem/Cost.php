<?php

require_once 'InputItem.php';

class Cost extends InputItem{
    #[\Override]
    protected function validate($value) {
        if (empty($value)) {
            throw new Exception("簡易見積が未実施です");
        }
        if (!is_numeric($value)) {
            throw new Exception("簡易見積価格が不正です");
        }
        return true;
    }
}

?>