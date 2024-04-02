<?php

require_once 'InputItemModel.class.php';

class Company extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("会社名が未入力です");
        }
        return true;
    }
}
