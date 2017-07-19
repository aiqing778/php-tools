<?php

require 'vendor/autoload.php';
require('progress_bar.php');

use Sunra\PhpSimple\HtmlDomParser;

$mod = $argv[1];
$page = intval($argv[2]);

$fd = fopen('/tmp/'.$mod.'.csv', 'w');

for ($i = 1; $i <= $page; ++$i) {
	$url = "http://www.woshipm.com/category/".$mod;
	if ($i != 1) {
		$url .= '/page/'.$i;
	}

	$html = HtmlDomParser::file_get_html($url);

	$items = $html->find('.stream-list-item');
	foreach ($items as $n=>$item) {
		$title = $item->find('.stream-list-title a', 0)->title;
		$href = $item->find('.stream-list-title a', 0)->href;
		$time = $item->find('.stream-list-meta', 0)->last_child()->plaintext;
		$read = $item->find('.post-views', 0)->plaintext;
        $read = trim(mb_strstr(trim($read), " "));
		$mark = $item->find('.post-marks', 0)->plaintext;
        $mark = trim(mb_strstr(trim($mark), " "));
		$like = $item->find('.post-likes', 0)->plaintext;
        $like = trim(mb_strstr(trim($like), " "));
		$hyperlink = "=HYPERLINK(\"$href\",\"$title\")";
		fputcsv($fd, array($hyperlink,$time,$read,$mark,$like));
	}
	progress_bar($i, $page);
}

fclose($fd);
