<?php

require 'vendor/autoload.php';
require('progress_bar.php');

use Sunra\PhpSimple\HtmlDomParser;

function simple_http_get($url)
{
    $cookie = "BIGipServersbs_home19loucom_pool=1242235146.20480.0000; _Z3nY0d4C_=37XgPK9h-%3D1920-1920-1920-513";
    $fp = curl_init($url);
    curl_setopt($fp, CURLOPT_HEADER, FALSE);
    curl_setopt($fp, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($fp, CURLOPT_COOKIE, $cookie);
    $s = curl_exec($fp);
    curl_close($fp);
    return iconv('GBK','UTF-8//IGNORE', $s);
}

function get_detail_item($item)
{
    $str = $item->innertext;
    return preg_replace('/<span>.*<\/span>/', '', $str);
}

function get_detail($url)
{
	$html = HtmlDomParser::str_get_html(simple_http_get($url));
    $items = $html->find('.house-details td');

    $detail = array();
    foreach ($items as $n=>$item)
    {
        array_push($detail, get_detail_item($item));
    }
    return $detail;
}

function export_all($page, $all)
{
    $count = 0;

    $fd = fopen('/tmp/home.csv', 'w');

    for ($i = 1; $i <= $page; ++$i) {
        $url = "http://home.19lou.com/forum-94-$i.html?order=createdat&digest=true";

        $html = HtmlDomParser::str_get_html(simple_http_get($url));
        $items = $html->find('table tr');

        foreach ($items as $n=>$item) {
            if ($n === 0)
            {
                continue;
            }
            $title = $item->find('.title a', 0)->plaintext;
            $href = 'http:'.$item->find('.title a', 0)->href;
            $createdat = $item->find('.author span', 0)->plaintext;
            $replies = $item->find('.num em', 0)->plaintext;
            $pv = $item->find('.num span', 0)->plaintext;

            $title_href = "=HYPERLINK(\"$href\",\"$title\")";
            $summary = array($title_href,$createdat,$replies,$pv);
            $detail = get_detail($href);
            fputcsv($fd, array_merge($summary,$detail));

            $count += 1;
            progress_bar($count, $all);
        }
    }

    fclose($fd);
}

export_all(13, 382);
