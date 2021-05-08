<?php
if (Router::$action == 'save') {
	if (isset($_POST['order_num'], $_POST['organization'], $_POST['agent'])) {
		$user_data = ['name' => $_POST['order_num'], 'meta' => ['organization' => $_POST['organization'], 'agent' => $_POST['agent']]];
		$order = MS::apiPost(['entity' => 'customerorder', 'data' => $user_data, 'result_convert' => 1]);
		if (!isset($order['errors'])) {
			if (isset($order['id'])) {
				$order_state = isset($order['state']['meta']['href']) ? explode('/', $order['state']['meta']['href']) : '';
				if (!empty($order_state)) $order_state = current(array_reverse($order_state));
				$sql = "INSERT INTO orders (ms_order_id, ms_order_num, ms_org, ms_agent, ms_state) VALUES (?, ?, ?, ?, ?)";
				DB::setConnection(Config::getDbConfig());
				$query_result = DB::runQuery($sql, 'ins', [$order['id'], $_POST['order_num'], $_POST['organization'], $_POST['agent'], $order_state]);
				if (isset($query_result['error'])) {
					Config::$sys_mess = ['err' => []];
					foreach ($query_result['error'] as $item) {
						if (!empty($item)) Config::$sys_mess['err'][] = $item;
					}
				} else {
					Config::$sys_mess = ['ok' => ['Заказ сохранен.']];
				}
			}
		} else {
			Config::$sys_mess = ['err' => []];
			foreach ($order['errors'] as $item) {
				if (!empty($item['error'])) Config::$sys_mess['err'][] = $item['error'];
			}
		}
	} else {
		Config::$sys_mess = ['err' => ['Ошибка получения данных формы!', 'Создайте заказ заново.']];
	}
	include_once 'template/main.tpl';
} else {
	$organizations = MS::buildListFromApi(['entity' => 'organization', 'data' => ['rows' => ['id' => 'name']]]);
	$agents = MS::buildListFromApi(['entity' => 'counterparty', 'data' => ['rows' => ['id' => 'name']]]);
	include_once 'template/orderform.tpl';
}
?>