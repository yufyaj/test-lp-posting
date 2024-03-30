<?php

require_once 'InputItem.php';

enum EnumType: int{
    // PR
    case PR = 1;
    // 集客
    case MARKETING = 2;
    // 求人
    case RECRUITMENT = 3;

    public function text() {
        return match($this) {
            EnumType::PR => "PR",
            EnumType::MARKETING => "集客",
            EnumType::RECRUITMENT => "求人",
        };
    }
}

class Type extends InputItem {
    private function validate($value) {
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
?>