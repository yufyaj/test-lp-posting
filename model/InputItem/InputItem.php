<?php

require_once 'InputItem.php';

class InputItem{
    protected $value;

    public function __construct($value) {
        if (self::validate($value)) {
            $this->value = $value;
        }
    }

    public function getValue() {
        return $this->value;
    }

    private function validate($value) {
        // バリデーション処理をここに実装する
        // ここでは基底クラスでは常にtrueを返す（サブクラスでオーバーライドする）
        return true;
    }
}

?>