<?php

require_once 'InputItemModel.class.php';

class Privacy extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("個人情報の取り扱いに同意してください");
        }
        if (!$value) {
            throw new Exception("個人情報の取り扱いに同意してください");
        }
        return true;
    }
}
