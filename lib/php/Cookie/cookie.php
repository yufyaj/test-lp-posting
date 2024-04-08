<?php

namespace lib\Cookie;

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/php/Session/managementSession.php';

use Exception;
use lib\Session as session;

class Cookie
{
    private $session;
    private $token;
    private $key;

    /**
     * インスタンスを生成する
     */
    public function __construct($key, $token = null)
    {
        $this->session = session\ManagementSession::getInstance();
        $this->session->createSession();

        //トークンをセット
        if ($token == null) {
            $token = sha1(uniqid(mt_rand(), true));
            $_SESSION[$key] = $token;
        }

        $this->token = $token;
        $this->key = $key;
    }

    /**
     * トークンを返す
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * トークンの正常判定
     * @param $token String トークン
     * @return bool True:正常, False:異常
     */
    public function isValidToken()
    {
        $this->session = session\ManagementSession::getInstance();
        $this->session->createSession();

        $session_token = null;
        if (isset($_SESSION[$this->key])) {
            $session_token = $_SESSION[$this->key];
        }

        if (empty($session_token)) {
            return false;
        }

        if ($session_token !== $this->token) {
            unset($_SESSION[$this->key]); //トークン破棄
            return false;
        }

        unset($_SESSION[$this->key]); //トークン破棄
        return true;
    }
}
