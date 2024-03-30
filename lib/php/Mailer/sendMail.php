<?php

namespace lib\Mailer;

function sendMail($to, $cc, $subject, $content) {
    /** 内部文字エンコーディングをUTF-8に設定します*/
    mb_language('ja');
    mb_internal_encoding('UTF-8');

    // 送信元メールアドレス
    $from = 'webmas1@pos-con.com';

    // 送信元の表示名
    $from_name = 'System';

    // 追加のヘッダー
    $additional_headers = "From: $from_name <$from>\r\n";
    $additional_headers .= "Reply-To: $from\r\n";
    $additional_headers .= "MIME-Version: 1.0\r\n";
    $additional_headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    $additional_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    // $additional_headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . "\r\n";

    // メールを送信
    if (mb_send_mail($to, $subject, $content, $additional_headers, '-f' . $from)) {
        echo 'メールが送信されました。';
    } else {
        echo 'メールの送信に失敗しました。';
    }
}
?>