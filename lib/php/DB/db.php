<?php
    namespace lib\db;

    require_once $_SERVER['DOCUMENT_ROOT'].'/lib/php/Logger/logger.php';
    require_once 'config.php';

    use lib\logger\Logger;

    class ConnectDb {

        private static $singleton;
        private static $log;
        private static $pdo;

        /**
         * インスタンスを生成する
         */
        public static function getInstance()
        {
            if (!isset(self::$singleton)) {
                self::$singleton = new ConnectDb();
            }
            if (!isset(self::$log)) {
                self::$log = Logger::getInstance();
            }
            if (!isset(self::$pdo)) {
                $dsn = sprintf('mysql:host=%s; dbname=%s; charset=utf8', config::HOST, config::DB_NAME); 
                try{
                    self::$pdo = new \PDO($dsn, config::USER, config::PASSWORD, array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION));    
                } catch (\PDOException $errorMessage) {
                    $errorMessage = 'データベースエラー';
                    self::$log->info($errorMessage);
                    throw $errorMessage;
                }
            }
            return self::$singleton;
        }

        /**
         * 全レコード取得して返す
         */
        public function fetchAll($sql) {
            try{
                //$pdoにあるqueryメソッドを呼び出してSQLを実行
                $stmt = self::$pdo->query($sql);

                //出力結果を$rowに代入
                $rows = $stmt->fetchAll();

                return $rows;
            } catch (\PDOException $errorMessage) {
                $errorMessage = 'データベースエラー';
                self::$log->info($errorMessage);
                return null;
            }
        }
    }
?>