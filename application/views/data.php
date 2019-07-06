<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php

// Make sure format of data is correct.
function formatJson($data) {
	$debug = false; 
	$json = '';

	if ($debug) {
		$json = json_encode($data, JSON_PRETTY_PRINT);
	} else {
		$json = json_encode($data, JSON_UNESCAPED_SLASHES);
	}
	
	// There are some symbols included on initial import of data to database.
	$json = str_replace('<\/', '</', $json);
	$json = str_replace("\u201c", '', $json);
	$json = str_replace("\u201d", '', $json);
	$json = str_replace("\u2013", '-', $json);
	$json = str_replace("\u2019", '', $json);
	$json = str_replace("\u00f1", 'ñ', $json);
	$json = str_replace('<br />', '\n', $json);
	$json = str_replace('\\\n', '\n', $json);

	if ($debug) {
		$json = '<pre>' . $json . '</pre>';
	}
	return $json;
}

if (isset($data) && $data) {
	echo formatJson($data);
} else {
	echo 'empty';
}

?>