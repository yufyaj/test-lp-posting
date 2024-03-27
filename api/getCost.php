<?php

require $_SERVER['DOCUMENT_ROOT'].'/lib/php/Logger/logger.php';
require $_SERVER['DOCUMENT_ROOT'].'/lib/php/DB/db.php';

use lib\Logger as logger;
use lib\db as db;

$log = logger\Logger::getInstance();
$db = db\ConnectDb::getInstance();

if($_SERVER["REQUEST_METHOD"] != "GET"){
    // ブラウザからHTMLページを要求された場合
}else{
    // フォームからGETによって要求された場合

    $type = $_GET['type'];
    $state = $_GET['state'];
    $city = $_GET['city'];
    $busu = $_GET['busu'];
    $log->info("type: {$type}, state: {$state}, city: {$city}, busu: {$busu}");

    // 必須入力チェック
    if (empty($type) || empty($state) || empty($city) || empty($busu)) {
        $result = array('result' => false,
                        'is_empty_type' => empty($type),
                        'is_empty_state' => empty($state),
                        'is_empty_city' => empty($city),
                        'is_empty_busu' => empty($busu),
                        'cost' => 0);

        echo json_encode($result);
        return;
    }
        
    //SQLを作成
    $sql = "SELECT (cost * $busu) AS cost FROM cost WHERE state = '$state' AND city = '$city' AND type = '$type'";
    
    //$pdoにあるqueryメソッドを呼び出してSQLを実行
    //出力結果を$rowに代入
    $rows = $db->fetchAll($sql);
    
    //出力結果をそれぞれの配列に格納
    $cost = array_column($rows,'cost')[0];

    $result = array('result' => true,
                    'is_empty_type' => empty($type),
                    'is_empty_state' => empty($state),
                    'is_empty_city' => empty($city),
                    'is_empty_busu' => empty($busu),
                    'cost' => $cost);
    echo json_encode($result);    
    return;
}
?>