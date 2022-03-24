<?php

namespace Includes\System;

use \PDO;

/**
 *
 */
class Log
{
	/**
	 * @var
	 */
	private $database;
	/**
	 * @var array|false
	 */
	public $logTypes = array();

	/**
	 * @param $database
	 */
	function __construct($database)
	{
		$this->database = $database;
		$this->logTypes = $this->getLogTypes();

	}

	/**
	 * @param $user_agent
	 * @return string
	 */
	function get_browser_name($user_agent)
	{
		$t = strtolower($user_agent);
		$t = " " . $t;
		if (strpos($t, 'opera') || strpos($t, 'opr/')) return 'Opera';
		elseif (strpos($t, 'edge')) return 'Edge';
		elseif (strpos($t, 'chrome')) return 'Chrome';
		elseif (strpos($t, 'safari')) return 'Safari';
		elseif (strpos($t, 'firefox')) return 'Firefox';
		elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) return 'Internet Explorer';
		return 'Unkown';
	}

	/**
	 * @return string
	 */
	function getOS()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$os_platform = "Unknown OS Platform";
		$os_array = array(
			'/windows nt 10/i' => 'Windows 10',
			'/windows nt 6.3/i' => 'Windows 8.1',
			'/windows nt 6.2/i' => 'Windows 8',
			'/windows nt 6.1/i' => 'Windows 7',
			'/windows nt 6.0/i' => 'Windows Vista',
			'/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
			'/windows nt 5.1/i' => 'Windows XP',
			'/windows xp/i' => 'Windows XP',
			'/windows nt 5.0/i' => 'Windows 2000',
			'/windows me/i' => 'Windows ME',
			'/win98/i' => 'Windows 98',
			'/win95/i' => 'Windows 95',
			'/win16/i' => 'Windows 3.11',
			'/macintosh|mac os x/i' => 'Mac OS X',
			'/mac_powerpc/i' => 'Mac OS 9',
			'/linux/i' => 'Linux',
			'/ubuntu/i' => 'Ubuntu',
			'/iphone/i' => 'iPhone',
			'/ipod/i' => 'iPod',
			'/ipad/i' => 'iPad',
			'/android/i' => 'Android',
			'/blackberry/i' => 'BlackBerry',
			'/webos/i' => 'Mobile'
		);

		foreach ($os_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$os_platform = $value;
			}
		}
		return $os_platform;
	}

	/**
	 * @return string
	 */
	function getBrowser()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browser = "Unknown Browser";
		$browser_array = array(
			'/msie/i' => 'Internet Explorer',
			'/firefox/i' => 'Firefox',
			'/safari/i' => 'Safari',
			'/chrome/i' => 'Chrome',
			'/edge/i' => 'Edge',
			'/opera/i' => 'Opera',
			'/netscape/i' => 'Netscape',
			'/maxthon/i' => 'Maxthon',
			'/konqueror/i' => 'Konqueror',
			'/mobile/i' => 'Handheld Browser'
		);

		foreach ($browser_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$browser = $value;
			}
		}

		return $browser;
	}

	/**
	 * @param $logType
	 * @param string $detail
	 */
	function logThis($logType, $detail = "")
	{

		$db_connection = $this->database::$db;
		if ($this->checkLogType($logType)) {

			//sunucuda php_browscap.ini aktif ise daha okunaklı browser bilgisi alınabilir.
			if (function_exists('get_browser')) {
				$browser_info = $this->get_browser_name($_SERVER['HTTP_USER_AGENT']);
				$browser = array();
				$browser["parent"] = $this->getBrowser();
				$browser["platform"] = $this->getOS();
			} else {
				$browser['parent'] = "";
				$browser['platform'] = "";
			}

			//echo $browser['parent'];
			//echo $browser['platform'];
			$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
			$user_ip = $this->getUserIP();
			$query = $db_connection->prepare('INSERT INTO logs (user_id, log_datetime, client_ip, log_type, log_page, log_query_string, log_detail,log_browser,log_os,log_http_user_agent) VALUES (:user_id, NOW(), :client_ip, :log_type, :log_page, :log_query_string, :log_detail,:log_browser,:log_os,:log_http_user_agent)');

			$query->bindParam(':user_id', $user_id);
			//$db_connection->bind(':log_datetime', date("Y-m-d h:i:s"));
			$query->bindParam(':client_ip', $user_ip);
			$query->bindParam(':log_type', $logType);
			$query->bindParam(':log_page', $_SERVER['REQUEST_URI']);
			//$query->bindParam(':log_page', $log_page);
			$query->bindParam(':log_query_string', $_SERVER['QUERY_STRING']);
			$query->bindParam(':log_http_user_agent', $_SERVER['HTTP_USER_AGENT']);
			$query->bindParam(':log_detail', $detail);
			$query->bindParam(':log_browser', $browser['parent']);
			$query->bindParam(':log_os', $browser['platform']);
			$query->execute();
		} else {
			die("logtype bulunmuyor. ekleyiniz. #" . $logType);
		}

	}

	/**
	 * @return array|false
	 */
	public function getLogTypes()
	{
		$db_connection = $this->database::$db;
		$query = $db_connection->query('SELECT * FROM log_types');
		$query->execute();
		$logs = $query->fetchAll(PDO::FETCH_ASSOC);
		if ($query->rowCount() > 0) {
			foreach ($logs as $key => $log) {
				$log_types[$log['log_key']] = $log['log_val'];
			}
			return $log_types;
		}
		return false;


	}

	/**
	 * @param $log_val
	 * @return bool
	 */
	function checkLogType($log_val)
	{
		$db_connection = $this->database::$db;
		$query = $db_connection->prepare('SELECT * FROM log_types WHERE log_val=:log_val');
		$query->bindParam(':log_val', $log_val);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		if ($query->rowCount() > 0) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * @return mixed|string
	 */
	function getUserIP()
	{

		if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED']) && filter_var($_SERVER['HTTP_X_FORWARDED'], FILTER_VALIDATE_IP)) {
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		} elseif (isset($_SERVER['HTTP_FORWARDED']) && filter_var($_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
			$ip = $_SERVER['HTTP_FORWARDED'];
		} elseif (isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = "N/A";
		}

		return $ip;
	}

	/**
	 *
	 */
	function __destruct()
	{
		//unset($this);
	}
}
