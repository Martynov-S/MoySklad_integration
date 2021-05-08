<?php
class MS
{
	private static $api_url = 'https://online.moysklad.ru/api/remap/1.1/entity/';
	private static $meta_entity_decode = ['agent' => 'counterparty', 'organization' => 'organization', 'state' => 'state'];
	
	private static function apiResponse($args = []) {
		$curl_host = self::$api_url.(isset($args['entity']) ? $args['entity'] : '');
		$curl_auth = Config::getApiUserPwd();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curl_host);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $curl_auth);
		if (!empty($args['type'])) {
			if ($args['type'] == 'POST' && !empty($args['post_data'])) {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $args['post_data']);
			} elseif ($args['type'] == 'PUT') {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $args['post_data']);
			}
		}
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$return = curl_exec($ch);
		if ($return === false) {
			var_dump(curl_error($ch));die;
		} else {
			$ch_info = curl_getinfo($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$header = substr($return, 0, $ch_info['header_size']);
			$body = substr($return, $ch_info['header_size']);
		}
		curl_close($ch);
		
		return $body;
	}
	
	public static function buildListFromApi($args = []) {
		$result = [];
		$data = isset($args['data']) ? $args['data'] : [];
		$api_data = self::apiEntities($args);
		foreach ($data as $key => $data_i) {
			if (isset($api_data[$key]) && is_array($api_data[$key])) {
				$list_items = $api_data[$key];
				foreach ($list_items as $item) {
					foreach ($data_i as $list_key => $list_val) {
						if (isset($item[$list_key], $item[$list_val])) {
							$result[$item[$list_key]] = $item[$list_val];
						}
					}
				}
			}
		}
		return $result;
	}
	
	private static function buildApiMeta($data = []) {
		$result = [];
		foreach ($data as $key => $value) {
			if (isset(self::$meta_entity_decode[$key])) {
				$result[$key] = [
							'meta' => [
								'href' => self::$api_url.self::$meta_entity_decode[$key].'/'.$value, 
								'type' => self::$meta_entity_decode[$key],
								'mediaType' => 'application/json'
							]
				];
			}
		}
		return $result;
	}
	
	public static function apiPost($args = []) {
		$result = [];
		if (isset($args['entity'], $args['data'])) {
			$request_type = !empty($args['type']) ? $args['type'] : 'POST';
			$post_body = '';
			$data = [];
			foreach ($args['data'] as $key => $value) {
				if ($key == 'meta') {
					if (is_array($value)) {
						foreach (self::buildApiMeta($value) as $meta_key => $meta_val) {
							$data[$meta_key] = $meta_val;
						}
					}
				} else {
					$data[$key] = $value;
				}
			}
			$post_body = json_encode($data);
			$api_result = self::apiResponse(['entity' => $args['entity'], 'type' => $request_type, 'post_data' => $post_body]);
			$result = $api_result;
			if (isset($args['result_convert'])) $result = json_decode($api_result, true);
		}
		return $result;
	}
	
	public static function apiEntities($args = []) {
		$result = [];
		if (isset($args['entity'])) {
			$result = json_decode(self::apiResponse(['entity' => $args['entity']]), true);
		}
		return $result;
	}
}
?>