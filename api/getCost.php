<?php

require $_SERVER['DOCUMENT_ROOT'] . '/lib/php/Logger/logger.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/php/DB/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/InputValidateQueryGetCostParameter.class.php';
$pattern = $_SERVER['DOCUMENT_ROOT'] . '/model/InputItem/*.php';
foreach (glob($pattern) as $filename) {
    require_once $filename;
}

use lib\Logger as logger;
use lib\db as db;

$log = logger\Logger::getInstance();
$db = db\ConnectDb::getInstance();

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    // ブラウザからHTMLページを要求された場合
} else {
    // フォームからGETによって要求された場合
    $inputItem = new InputValidateQueryGetCostParameter($_GET);

    $responseJson = $inputItem->getResponseJson();
    if (!json_decode($responseJson)->Result) {
        echo $responseJson;
        return;
    }

    //SQLを作成
    $sql = "SELECT (cost * " . $inputItem->getBusu()->getValue() . ") AS cost FROM cost WHERE state = '" . $inputItem->getState()->getValue() . "'
                                                                                  AND city = '" . $inputItem->getCity()->getValue() . "' 
                                                                                  AND type = '" . $inputItem->getType()->getValue() . "'";

    //$pdoにあるqueryメソッドを呼び出してSQLを実行
    //出力結果を$rowに代入
    $rows = $db->fetchAll($sql);

    //出力結果をそれぞれの配列に格納
    $cost = array_column($rows, 'cost')[0];
    $inputItem->setCost($cost);

    $responseJson = $inputItem->getResponseJson();
    if (!json_decode($responseJson)->Result) {
        echo $responseJson;
        return;
    }

    echo $responseJson;
    return;
}
