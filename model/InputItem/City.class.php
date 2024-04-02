<?php

require_once 'InputItemModel.class.php';

class City extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("市区町村が未選択です");
        }
        return true;
    }
}
