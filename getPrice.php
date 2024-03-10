<?php

require 'Logger/logger.php';

$log = Logger::getInstance();

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
                        'price' => 0);

        echo json_encode($result);
        return;
    }

    // TODO: ここは共通化すべきでは？
    $db['dbname'] = "mariadb";  // データベース名
    $db['user'] = "mariadb";  // ユーザー名
    $db['pass'] = "mariadb";  // ユーザー名のパスワード
    $db['host'] = "127.0.0.1:3306";  // DBサーバのURL
    
    $dsn = sprintf('mysql:host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']); 
    
    try {
        //PDOを使ってMySQLに接続
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        
        //SQLを作成
        $sql = "SELECT (price * $busu) AS price FROM price WHERE state = '$state' AND city = '$city' AND type = '$type'";
        $log->info($sql);
        
        //$pdoにあるqueryメソッドを呼び出してSQLを実行
        $stmt = $pdo->query($sql);
    
        //出力結果を$rowに代入
        $rows = $stmt->fetchAll();
        
        //出力結果をそれぞれの配列に格納
        $price = array_column($rows,'price')[0];
    
        $result = array('result' => true,
                        'is_empty_type' => empty($type),
                        'is_empty_state' => empty($state),
                        'is_empty_city' => empty($city),
                        'is_empty_busu' => empty($busu),
                        'price' => $price);
        echo json_encode($result);
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラー';
        $log->info($errorMessage);
    }
    
    return;
}
?>