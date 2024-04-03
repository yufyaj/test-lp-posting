<?php

namespace lib\Session;

class ManagementSession
{
    private static $singleton;
    private static $session_name;
    private static $session_path;

    /**
     * インスタンスを生成する
     */
    public static function getInstance()
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new ManagementSession();
        }
        if (!isset(self::$session_path)) {
            self::$session_path = $_SERVER['DOCUMENT_ROOT'] . '/session';
        }
        if (!isset(self::$session_name)) {
            self::$session_name = 'POSCON';
        }
        return self::$singleton;
    }

    /**
     * セッションを作成する
     */
    private static function createSession()
    {
        ini_set('session.save_path', self::$session_path);
        if (session_status() == PHP_SESSION_NONE) {
            session_name('self::$session_name');
            session_start();
            setcookie(session_name(), session_id());
        }
    }

    /**
     * トークンを作成
     * @param key
     * @return トークン値
     */
    public static function createToken($key)
    {
        self::createSession();

        //トークンをセット
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION[$key] = $token;

        return $token;
    }


    /**
     * トークンの正常判定
     * @param $token String トークン
     * @return bool True:正常, False:異常
     */
    public static function isValidToken($key, $token)
    {
        self::createSession();
        $session_token = $_SESSION[$key];

        if (empty($session_token)) {
            return false;
        }

        if ($session_token !== $token) {
            unset($_SESSION[$key]); //トークン破棄
            return false;
        }

        unset($_SESSION[$key]); //トークン破棄
        return true;
    }
}
