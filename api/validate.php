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
    $inputItem = new InputValidateQueryAllParameter($_GET);

    $responseJson = $inputItem->getResponseJson();
    echo $responseJson;
    return;
}
