<?php

namespace lib\Mailer;

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/php/Logger/logger.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/php/Cookie/cookie.php';
require_once 'config.php';

use lib\logger\Logger;
use lib\Cookie\Cookie;

class Mailer
{
	//変換前の文字
	private static $replaceStrBefore = array('①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '№', '㈲', '㈱', '髙');
	//変換後の文字
	private static $replaceStrAfter = array('(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)', 'No.', '（有）', '（株）', '高');

	private static $singleton;
	private static $log;
	private static $key = 'mailtoken';
	/**
	 * インスタンスを生成する
	 */
	public static function getInstance()
	{
		if (!isset(self::$singleton)) {
			self::$singleton = new Mailer();
		}
		if (!isset(self::$log)) {
			self::$log = Logger::getInstance();
		}
		return self::$singleton;
	}

	/**
	 * CSRF対策
	 */
	public static function createToken()
	{
		$cookie = new Cookie(self::$key);
		return $cookie->getToken();
	}

	/**
	 * メール送信
	 */
	public static function sendMail($to, $cc, $subject, $body, $token)
	{
		if (empty($token)) {
			return false;
		}
		$cookie = new Cookie(self::$key, $token);
		if (!$cookie->isValidToken()) {
			return false;
		}

		/** 内部文字エンコーディングをUTF-8に設定します*/
		mb_language('uni');
		mb_internal_encoding('UTF-8');

		// 送信元メールアドレス
		$from = 'webmas1@pos-con.com';

		// 送信元の表示名
		$from_name = 'System';

		// 追加のヘッダー
		$additional_headers = self::createHeader($from_name, $from);
		// メールを送信
		if (mb_send_mail($to, $subject, $body, $additional_headers, '-f' . $from)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ヘッダーを作成する
	 */
	private static function createHeader($from_name, $from)
	{
		$additional_headers = "From: $from_name <$from>\r\n";
		$additional_headers .= "Reply-To: $from\r\n";
		$additional_headers .= "MIME-Version: 1.0\r\n";
		$additional_headers .= "Content-Type: text/plain; charset=utf-8\r\n";
		$additional_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

		return $additional_headers;
	}
}
