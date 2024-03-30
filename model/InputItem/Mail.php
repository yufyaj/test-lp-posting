<?php

require_once 'InputItem.php';

class Mail extends InputItem{
    private function validate($value) {
        if (empty($value)) {
            throw new Exception("メールアドレスが未入力です");
        }

        $mailaddress_array = explode('@',$value);
        if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-zA-Z]+(\.[!#%&\-_0-9a-zA-Z]+)+$/", "$value") && count($mailaddress_array) ==2){
            return true;
        }
        throw new Exception("メールアドレスの形式が正しくありません");
    }
}

?>