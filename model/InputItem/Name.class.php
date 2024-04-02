<?php

require_once 'InputItemModel.class.php';

class Name extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("担当者名が未入力です");
        }
        return true;
    }
}
