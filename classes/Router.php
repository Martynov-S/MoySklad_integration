<?php
class Router
{
	public static $module = 'main';
	public static $action = 'index';
	public static $params = [];
	
	public static function route($request_url = null) {
		if (is_null($request_url)) $request_url = $_SERVER['REQUEST_URI'];
		$request_arr = explode("?", $request_url);
		$request_path = $request_arr[0];
		$params = explode('/', trim($request_path, '/'));
		if (!empty($params)) {	
			if (!empty($params[0])) {
				$module = $params[0];
				if (!file_exists("module/mod_$module.php")) $module = 'main';
				self::$module = $module;
			}
			if (!empty($params[1])) self::$action = $params[1];
			self::$params = array_slice($params, 2);
		}
	}
	
	public static function checkParams($allow) {
		$result = false;
		$uri_param = implode('/', self::$params);
		if (empty($uri_param) || preg_match('|^'.$allow.'$|', $uri_param) == 1) $result = true;
		return $result;
	}
}
?>