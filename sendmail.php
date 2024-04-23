<?php
require_once 'lib/php/Logger/logger.php';
require_once 'lib/php/Mailer/mailer.php';
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
foreach (glob($pattern) as $filename) {
    require_once $filename;
}

use lib\Logger as logger;
use lib\Mailer as mailer;

$log = logger\Logger::getInstance();
$mailer = mailer\Mailer::getInstance();

$inputItem = new InputValidateQueryAllParameter($_POST);

$resultObject = json_decode($inputItem->getResponseJson());
$errorMessage = "";
$result = false;
if (!$resultObject->Result) {
    $result = false;
    $errorMessage = $resultObject->ErrorMessage;
} else {
    /** tokenもオブジェクト化できそう */
    $result = $mailer->sendMail(
        $inputItem->getMail()->getValue(),
        "",
        "サンプルメール",
        "これはサンプルメールです",
        $_POST["token"]
    );
}
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
            window.location = "/?type=<?= $inputItem->getType()->getValue() ?>&state=<?= $inputItem->getState()->getValue() ?>&city=<?= $inputItem->getCity()->getValue() ?>&busu=<?= $inputItem->getBusu()->getValue() ?>&cost=<?= $inputItem->getCost()->getValue() ?>&company=<?= $inputItem->getCompany()->getValue() ?>&name=<?= $inputItem->getName()->getValue() ?>&mail=<?= $inputItem->getMail()->getValue() ?>"
        }
    </script>
</head>

<body>
    <div class="container mx-auto bg-white h-full">
        <h1 class="text-xl py-3 font-bold text-center">メール送信結果</h1>

        <div class="bg-slate-100 my-5 py-5 text-gray-700">
            <div class="py-5 px-5 lg:px-10 bg-white grid gap-1 w-95/100 container mx-auto">
                <?php
                if ($result) {
                    echo 'メールを送信しました';
                } else {
                    echo 'メール送信に失敗しました';
                    echo $errorMessage;
                }
                ?>
                <button class="rounded text-white bg-gray-400 h-10 w-52" onclick="back()" type="button">トップページへ戻る</button>
            </div>
        </div>
    </div>
</body>

</html>