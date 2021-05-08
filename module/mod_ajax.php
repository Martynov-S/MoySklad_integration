<?php
ob_end_clean();
if (Router::$action == 'state_change') {
	$result = [];
	if (!empty($_POST['entity_id']) && !empty($_POST['order_id']) && !empty($_POST['new_state'])) {
		$user_data = ['meta' => ['state' => $_POST['new_state']]];
		$order = MS::apiPost(['entity' => 'customerorder/'.$_POST['entity_id'], 'data' => $user_data, 'result_convert' => 1, 'type' => 'PUT']);
		if (isset($order['errors'])) $result['fail'] = $order['errors'];
		if (empty($result)) {
			$sql = "UPDATE orders SET ms_state = ? WHERE order_id = ?";
			DB::setConnection(Config::getDbConfig());
			$query_result = DB::runQuery($sql, 'upd', [$_POST['new_state'], $_POST['order_id']]);
			if ($query_result > 0) {
				$result['success'] = 1;
			} else {
				$result['fail'] = $query_result;
			}
		}
	} else {
		$result['fail'] = 'no data';
	}
	echo json_encode($result);
}
exit;
?>