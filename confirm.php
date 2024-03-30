<?php
    require_once 'lib/php/Logger/logger.php';
    require_once 'lib/php/Mailer/mailer.php';

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
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-161021607-1');
            gtag('config', 'AW-806634571');
        </script>
<body>
<div class="container mx-auto bg-white h-full">
    <h1 class="text-4xl py-5 font-bold text-center">入力内容確認</h1>

    <label>type: </label><label><?= $type ?></label>
    <label>state: </label><label><?= $state ?></label>
    <label>city: </label><label><?= $city ?></label>
    <label>busu: </label><label><?= $busu ?></label>
    <label>company: </label><label><?= $company ?></label>
    <label>name: </label><label><?= $name ?></label>
    <label>mail: </label><label><?= $mail ?></label>
    <label>privacy: </label><label><?= $privacy ?></label>
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
        <div>
            <a href="/?type=<?= $type ?>&state=<?= $state ?>&city=<?= $city ?>&busu=<?= $busu ?>&cost=<?= $cost ?>&company=<?= $company ?>&name=<?= $name ?>&mail=<?= $mail ?>&privacy=true">戻る</a>
        </div>
        <button>送信</button>
    </form>
</div>
</body>
</html>