<?php

$input = '
';

function fullyContains($needle, $haystack) {
	echo implode('-', $needle), ' ', implode('-', $haystack), ' ';;
	if ($needle[0] < $haystack[0]) {
		echo "smaller\n";
		return false;
	}
	if ($needle[1] > $haystack[1]) {
		echo "bigger\n";
		return false;
	}
	echo "contained\n";
	return true;
}

function partlyContains($needle, $haystack) {
	echo implode('-', $needle), ' ', implode('-', $haystack), ' ';
	if ($needle[1] < $haystack[0]) {
		echo "below\n";
		return false;
	}
	if ($needle[0] > $haystack[1]) {
		echo "above\n";
		return false;
	}
	echo "contained\n";
	return true;
}


$input = trim($input);
$input = explode("\n", $input);
$count = 0;
foreach ($input as $line) {
	$pairs = explode(',', $line);
	$pairs[0] = explode('-', $pairs[0]);
	$pairs[1] = explode('-', $pairs[1]);
	
	if (partlyContains($pairs[0], $pairs[1]) || partlyContains($pairs[1], $pairs[0])) {
		++$count;
	}
}

echo $count;
