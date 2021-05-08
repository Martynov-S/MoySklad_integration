<?php
class DB
{
	private static $dbh;
	
	public static function setConnection($config) {	
		self::$dbh = new PDO($config['dsn'],$config['user'],$config['pass']);
	}
	
	public static function runQuery($sql, $type, $data = [], $args = []) {
		$result = null;
		if (!empty($sql)) {
			$query = self::$dbh->prepare($sql);
			if ($query !== false) {
				$status = $query->execute($data);
				if ($status !== false) {
					if ($type == 'ins') {
						$result = self::$dbh->lastInsertId();
					} elseif ($type == 'upd') {
						$result = $query->rowCount();
					} elseif ($type == 'sel') {
						$result = $query->fetchAll(PDO::FETCH_ASSOC);
					} elseif ($type == 'col') {
						$result = $query->fetchColumn();
					} elseif ($type == 'limit') {
						$result = [];
						$counter = 0;
						$first = !empty($args['limit']['first']) ? $args['limit']['first'] : 1;
						$last = !empty($args['limit']['last']) ? $args['limit']['last'] : 1;
						while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
							$counter++;
							if ($counter < $first) continue;
							if ($counter > $last) break;
							$result[] = $row;
						}
					}
				} else {
					$result['error'] = $query->errorInfo();
				}
			} else {
				$result['error'] = self::$dbh->errorInfo();
			}
		}
		return $result;
	}
}
?>