<?php
if (Router::$action == 'list') {
	$allow_pattern = 'p\/\d+';
	if (!Router::checkParams($allow_pattern)) header('Location: /');
	
	$current_page = 1;
	if (isset(Router::$params[1])) $current_page = (int)Router::$params[1];
	$on_page = isset(Config::$app_config['orders_on_page']) ? Config::$app_config['orders_on_page'] : 25;
	DB::setConnection(Config::getDbConfig());
	$sql = "SELECT COUNT(order_id) FROM orders WHERE 1";
	$orders_cnt = DB::runQuery($sql, 'col', []);
	$max_page = ceil($orders_cnt / $on_page);
	if ($current_page > $max_page) header('Location: /orders/list');
	$limit = ['first' => (($current_page - 1) * $on_page + 1), 'last' => ($current_page * $on_page)];
	$sql = "SELECT order_id, ms_order_id, ms_order_num FROM orders WHERE 1 ORDER BY ts";
	$query_orders = DB::runQuery($sql, 'limit', [], ['limit' => $limit]);
	$orders = [];
	foreach ($query_orders as $order) {
		$orders[$order['ms_order_id']]['order_id'] = $order['order_id'];
		$orders[$order['ms_order_id']]['order_num'] = $order['ms_order_num'];
	}
	$filter = implode(';id=', array_keys($orders));
	if (!empty($filter)) $filter = '&filter=id='.$filter;
	$expands = '?expand=agent,organization,state';
	$ms_orders = MS::apiEntities(['entity' => 'customerorder'.$expands.$filter]);
	if (isset($ms_orders['rows']) && is_array($ms_orders['rows'])) {
		foreach ($ms_orders['rows'] as $order) {
			if (!isset($order['id'])) continue;
			$orders[$order['id']]['agent'] = isset($order['agent']['name']) ? $order['agent']['name'] : '';
			$orders[$order['id']]['organization'] = isset($order['organization']['name']) ? $order['organization']['name'] : '';
			$orders[$order['id']]['state'] = isset($order['state']['name']) ? $order['state']['name'] : '';
			$orders[$order['id']]['state_id'] = isset($order['state']['id']) ? $order['state']['id'] : '';
		}
	}
	$pager = ['prev' => '', 'next' => ''];
	if ($current_page > 1) $pager['prev'] = $current_page == 2 ? '/orders/list' : '/orders/list/p/'.($current_page - 1);
	if ($current_page < $max_page) $pager['next'] = '/orders/list/p/'.($current_page + 1);
	$states = MS::buildListFromApi(['entity' => 'customerorder/metadata', 'data' => ['states' => ['id' => 'name']]]);
	include_once 'template/orderslist.tpl';
} else {
	header('Location: /');
}
?>