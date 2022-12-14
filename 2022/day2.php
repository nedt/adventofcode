<?php

$input = '
';

$input = trim($input);
$input = explode("\n", $input);


function outcome($you, $me) {
	if ($me === $you) {
		return 0;
	}
	if ($me == 0) {
		return $you == 1 ? -1 : 1;
	} else if ($me == 1) {
		return $you == 2 ? -1 : 1;
	} else {
		return $you == 0 ? -1 : 1;
	}
}

function getMe($you, $outcome) {
	if ($outcome == 0) {
		return $you;
	}
	if ($you == 0) {
		return $outcome > 0 ? 1 : 2;
	} else if ($you == 1) {
		return $outcome > 0 ? 2 : 0;
	} else {
		return $outcome > 0 ? 0 : 1;
	}
}

$score = 0;
foreach ($input as $line) {
	$you = strtok($line, ' ');
	$me = strtok(' ');
	
	$you = ord($you) - ord('A');
	$me = ord($me) - ord('X');

	//$outcome = outcome($you, $me);
	$outcome = $me - 1;
	$me = getMe($you, $outcome);
	
	echo "$you $me $outcome\n";
	$score += $me + 1 + ($outcome + 1) * 3;
}

echo $score;
