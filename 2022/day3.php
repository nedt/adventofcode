<?php

$input = '
';

$input = trim($input);
$input = explode("\n", $input);
$sum = 0;
foreach ($input as $line) {
	$one = substr($line, 0, strlen($line) / 2);
	$same = preg_replace('%[^' . $one . ']%', '', $line);
	$same = $same[strlen($same) - 1];
	if ($same <= 'Z') {
		$prio = ord($same) - ord('A') + 27;
	} else {
		$prio = ord($same) - ord('a') + 1;		
	}
	echo $same, ' ', $prio, "\n";
	$sum += $prio;
}
echo $sum, "\n";

reset($input);
$sum = 0;
while ($first = current($input)) {
	$second = next($input);
	$third = next($input);
	$same = preg_replace('%[^' . $first . ']%', '', $second);
	$same = preg_replace('%[^' . $same . ']%', '', $third);
	$same = $same[strlen($same) - 1];
	if ($same <= 'Z') {
		$prio = ord($same) - ord('A') + 27;
	} else {
		$prio = ord($same) - ord('a') + 1;		
	}
	echo $same, ' ', $prio, "\n";
	$sum += $prio;

	next($input);
}
echo $sum, "\n";
