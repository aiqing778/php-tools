<?php

require('simple_html_dom.php');
require('progress_bar.php');

$mod = $argv[1];
$page = intval($argv[2]);

$fd = fopen('/tmp/'.$mod.'.csv', 'w');

for ($i = 1; $i <= $page; ++$i) {
	$url = "http://www.woshipm.com/category/".$mod;
	if ($i != 1) {
		$url .= '/page/'.$i;
	}

	$html = file_get_html($url);
	
	$items = $html->find('.ft');
	foreach ($items as $n=>$item) {
		$title = $item->find('.list-h3 a', 0)->title;
		$href = $item->find('.list-h3 a', 0)->href;
		$time = $item->find('.time', 0)->innertext;
		$read = $item->find('.read', 0)->innertext;
		$matches = NULL;
		preg_match('/[0-9]+/', $read, $matches);
		$read = $matches[0];
		$like = $item->find('.like', 0)->innertext;
		preg_match('/[0-9]+/', $like, $matches);
		$like = $matches[0];
		$hyperlink = "=HYPERLINK(\"$href\",\"$title\")";
		fputcsv($fd, array($hyperlink,$time,$read,$like));
	}
	progress_bar($i, $page);
}

fclose($fd);
