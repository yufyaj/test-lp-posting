<?php

namespace lib\Mailer;

require_once $_SERVER['DOCUMENT_ROOT'].'/lib/php/Logger/logger.php';
require_once 'sendMail.php';
require_once 'config.php';

use lib\logger\Logger;

class Mailer{
	//変換前の文字
	private static $replaceStrBefore = array('①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','№','㈲','㈱','髙');
	//変換後の文字
	private static $replaceStrAfter = array('(1)','(2)','(3)','(4)','(5)','(6)','(7)','(8)','(9)','(10)','No.','（有）','（株）','高');

	private static $singleton;
	private static $log;

	/**
	 * インスタンスを生成する
	 */
	public static function getInstance() {
		if (!isset(self::$singleton)) {
			self::$singleton = new Mailer();
		}
		if (!isset(self::$log)) {
			self::$log = Logger::getInstance();
		}
		return self::$singleton;
	}

	/**
	 * メール送信
	 */
	public function sendMail($type,$state,$city,$busu,$cost,$company,$name,$mail,$token) {
		if (self::isValidToken($token)) {
			return false;
		}
		sendMail($mail, null, "テストメール", "");

		return true;
	}

	/**
	 * トークンを作成
	 * @return トークン値
	 */
	public function createToken() {
		if (session_status() == PHP_SESSION_NONE) {
			session_name('PHPMAILFORMSYSTEM');
			session_start();
		}

		//トークンをセット
		$token = sha1(uniqid(mt_rand(), true));
		$_SESSION['mailform_token'] = $token;
		
		return $token;
	}

	/**
	 * トークンの正常判定
	 * @param $token String トークン
	 * @return bool True:正常, False:異常
	 */
	private function isValidToken($token) {
		//トークンチェック（CSRF対策）
		if(empty($_SESSION['mailform_token'])) {
			return false;
		}
		if($_SESSION['mailform_token'] !== $token){
			return false;
		}
		if(isset($_SESSION['mailform_token'])) {
			unset($_SESSION['mailform_token']);//トークン破棄
		}

		return true;
	}
}
?>
