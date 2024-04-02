<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/php/Logger/logger.php';
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
foreach (glob($pattern) as $filename) {
    require_once $filename;
}

use lib\logger as logger;

$log = logger\Logger::getInstance();

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    // ブラウザからHTMLページを要求された場合
} else {
    $type = $_GET['type'];
    $state = $_GET['state'];
    $city = $_GET['city'];
    $busu = $_GET['busu'];
    $cost = $_GET['cost'];
    $company = $_GET['company'];
    $name = $_GET['name'];
    $mail = $_GET['mail'];
    $privacy = $_GET['privacy'];

    $inputItem = new InputItem();
    $inputItem->setType($type);
    $inputItem->setState($state);
    $inputItem->setCity($city);
    $inputItem->setBusu($busu);
    $inputItem->setCost($cost);
    $inputItem->setCompany($company);
    $inputItem->setName($name);
    $inputItem->setMail($mail);
    $inputItem->setPrivacy($privacy);

    $responseJson = $inputItem->getResponseJson();
    echo $responseJson;
    return;
}
