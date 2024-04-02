<?php

require_once 'InputItemModel.class.php';

class Type extends InputItemModel
{
    #[\Override]
    protected function validate($value)
    {
        if (empty($value)) {
            throw new Exception("配布区分が未選択です");
        }
        $type = EnumType::tryfrom($value);
        if ($type == null) {
            throw new Exception("配布区分が不正です");
        }
        return true;
    }
}
