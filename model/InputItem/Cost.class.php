<?php

require_once 'InputItemModel.class.php';

class Cost extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("簡易見積が未実施です");
        }
        if (!is_numeric(str_replace(",", "", $value))) {
            throw new Exception("簡易見積価格が不正です");
        }
        return true;
    }
}
