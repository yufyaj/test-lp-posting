<?php
    require_once 'lib/php/Logger/logger.php';
    require_once 'lib/php/Mailer/mailer.php';

    use lib\Logger as logger;
    use lib\Mailer as mailer;

    $log = logger\Logger::getInstance();
    $mailer = mailer\Mailer::getInstance();

    $type = $_POST['type'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $busu = $_POST['busu'];
    $cost = $_POST['cost'];
    $company = $_POST['company'];
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $token = $_POST['token'];

    $mailer->sendMail($type,$state,$city,$busu,$cost,$company,$name,$mail,$token);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

</body>
</html>