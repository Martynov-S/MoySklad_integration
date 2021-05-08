<?php
class Config
{
	private static $db = [];
	private static $api = [];
	public static $app_config = [];
	public static $sys_mess;
	
	public static function init() {
		$conf_file = file_get_contents(ROOTDIR.'/inc/app.conf');
		$config = json_decode($conf_file, true);
		if (isset($config['db'])) self::$db = $config['db'];
		if (isset($config['api'])) self::$api = $config['api'];
		if (isset($config['cfg'])) self::$app_config = $config['cfg'];
		self::$sys_mess = '';
	}
	
	public static function getDbConfig() {
		return self::$db;
	}
	
	public static function getApiConfig() {
		return self::$api;
	}
	
	public static function getApiUserPwd() {
		$result = '';
		if (isset(self::$api['user'])) $result .= self::$api['user'];
		$result .= ':';
		if (isset(self::$api['pass'])) $result .= self::$api['pass'];
		return $result;
	}
}
?>