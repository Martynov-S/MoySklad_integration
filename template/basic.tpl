<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Работа с заказами</title>
		<link rel="stylesheet" type="text/css" href="/template/css/app.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="/template/js/app.js"></script>
	</head>
	<body>
		<?php
		if (!empty(Config::$sys_mess)) {
			$message_type = key(Config::$sys_mess);
			$message_str = implode('<br>', current(Config::$sys_mess));
			?>
			<div class="system-message <?=$message_type?>"><?=$message_str?></div>
			<?
		} ?>
		<div class="content">
			<?php
			if (Router::$module != 'main') {
				?>
				<div class="breads">
					<a href="/">На главную</a>
				</div>	
				<?
			} ?>
			<div class="wrap">
				<?=$content?>
			</div>
		</div>
	</body>
</html>