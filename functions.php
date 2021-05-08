<?php
function classAutoLoad($className) {
	$class_path = ['classes'];
	foreach ($class_path as $file_path) {
		$fileName = "$file_path/$className.php";
        if (file_exists($fileName)) {
            require_once ($fileName);
			break;
        }
    }
}

?>