<?php

namespace lib\Session;

class ManagementSession
{
    private static $singleton;

    /**
     * インスタンスを生成する
     */
    public static function getInstance()
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new ManagementSession();
        }
        return self::$singleton;
    }


    /**
     * セッションを作成する
     */
    public static function createSession()
    {
        $session_path = $_SERVER['DOCUMENT_ROOT'] . '/session';
        $session_name = 'POSCON';

        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.save_path', $session_path);
            session_name($session_name);
            session_start();
            setcookie(session_name(), session_id());
        }
    }
}
