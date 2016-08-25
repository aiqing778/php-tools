<?php

function progress_bar($passed, $total, $passedS='#', $remainS='.') {
	$_buffer = "Progress: [";
	$_progress = (int)($passed / $total * 100);
	$_buffer .= "$_progress%] [";
	$_N = 50;
	$_NP = (int)($_progress * $_N / 100);
	$_NR = $_N - $_NP;
	$_buffer .= str_repeat($passedS, $_NP);
	$_buffer .= str_repeat($remainS, $_NR);
	$_buffer .= "]";
	if ($_progress === 100) {
		$_buffer .= "\n";
	} else {
		$_buffer .= "\r";
	}
	echo $_buffer;
}

