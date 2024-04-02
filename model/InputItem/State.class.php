<?php

require_once 'InputItemModel.class.php';

class State extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("都道府県が未選択です");
        }
        return true;
    }
}
