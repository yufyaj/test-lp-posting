<?php
require_once 'lib/php/Logger/logger.php';
require_once 'lib/php/Mailer/mailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/EnumType.enum.php';

use lib\Logger as logger;
use lib\Mailer as mailer;

$log = logger\Logger::getInstance();
$mailer = mailer\Mailer::getInstance();

$type = $_GET['type'];
$state = $_GET['state'];
$city = $_GET['city'];
$busu = $_GET['busu'];
$cost = $_GET['cost'];
$company = $_GET['company'];
$name = $_GET['name'];
$mail = $_GET['mail'];
$privacy = $_GET['privacy'];
$token = $mailer->createToken();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <link rel="canonical" href="https://posting-m.com/">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="format-detection" content="telephone=no">
    <title>ポスティング見積.com -簡単3ステップでお見積！安心・信頼の高品質ポスティングをご提案します-</title>
    <meta name="Description" content="ポスティング見積.comでは、専門の担当者がお客様の要望をヒアリングし、配布エリア・配布単価・配布日程によって、最適な会社での御提案・お見積りをお届けいたします。一括見積のサイトと異なり、複数のポスティング会社から見積もりを取る必要はありません。わずらわしい業務は全てポスティング見積.comにお任せください！">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&display=swap" rel="stylesheet">
    <!-- tailwindCss -->
    <link href="./css/output.css" rel="stylesheet">
    <script src="https://posting-m.com/js/jquery-3.4.1.min.js"></script>
    <script src="/lib/js/utils.js"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-161021607-1');
        gtag('config', 'AW-806634571');
    </script>
    <script>
        function back() {
            window.location = "/?type=<?= $type ?>&state=<?= $state ?>&city=<?= $city ?>&busu=<?= $busu ?>&cost=<?= $cost ?>&company=<?= $company ?>&name=<?= $name ?>&mail=<?= $mail ?>"
        }
    </script>

<body>
    <div class="container mx-auto bg-white h-full">
        <h1 class="text-xl py-3 font-bold text-center">入力内容確認</h1>

        <div class="bg-slate-100 my-5 py-5 text-gray-700">
            <div class="py-5 px-5 lg:px-10 bg-white grid gap-1 w-95/100 container mx-auto">
                <label class="font-bold">配布区分: </label>
                <label class="h-8  bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= EnumType::from($type)->text() ?></label>
                <label class="font-bold mt-3">都道府県: </label>
                <label class="h-8 bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= $state ?></label>
                <label class="font-bold mt-3">市区町村: </label>
                <label class="h-8 bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= $city ?></label>
                <label class="font-bold mt-3">配布部数: </label>
                <label class="h-8 bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= $busu ?></label>
                <label class="font-bold mt-3">貴社名: </label>
                <label class="h-8 bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= $company ?></label>
                <label class="font-bold mt-3">ご担当者名: </label>
                <label class="h-8 bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= $name ?></label>
                <label class="font-bold mt-3">メールアドレス: </label>
                <label class="h-8 bg-slate-100 w-95/100 mx-5 indent-8 mh-auto flex items-center"><?= $mail ?></label>
                <form action="./sendmail.php" method="POST">
                    <input type="hidden" name="type" value="<?= $type ?>" />
                    <input type="hidden" name="state" value="<?= $state ?>" />
                    <input type="hidden" name="city" value="<?= $city ?>" />
                    <input type="hidden" name="busu" value="<?= $busu ?>" />
                    <input type="hidden" name="cost" value="<?= $cost ?>" />
                    <input type="hidden" name="company" value="<?= $company ?>" />
                    <input type="hidden" name="name" value="<?= $name ?>" />
                    <input type="hidden" name="mail" value="<?= $mail ?>" />
                    <input type="hidden" name="token" value="<?= $token ?>" />
                    <div class="grid grid-cols-2 gap-2 justify-items-center py-5">
                        <button class="rounded text-white bg-gray-400 h-10 w-20 lg:w-52" onclick="back()" type="button">戻る</button>
                        <button class="rounded px-2 text-white bg-amber-500 h-10 w-20 lg:w-52">送信</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>