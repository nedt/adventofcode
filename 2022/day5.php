<?php

$input = '
';


$input = trim($input, "\r\n");
list($inputStacks, $inputSteps) = explode("\n\n", $input, 2);
$inputStacks = explode("\n", $inputStacks);
$inputSteps = explode("\n", $inputSteps);

array_pop($inputStacks);
$stacks = [];
foreach ($inputStacks as $line) {
	$current = 0;
	for ($i = 1; $i < strlen($line); $i += 4) {
		++$current;
		$element = $line[$i];
		if (!isset($stacks[$current])) {
			$stacks[$current] = [];
		}
		if (ctype_alpha($element)) {
			array_unshift($stacks[$current], $element);
		}
	}
}
var_dump($stacks);

foreach ($inputSteps as $line) {
	list(, $count, , $from, , $to) = explode(' ', $line);
	echo "$count x $from -> $to\n";
	$lift = [];
	for (; $count > 0; --$count) {
		$lift[] = array_pop($stacks[$from]);
//		$stacks[$to][] = array_pop($stacks[$from]);
	}
	foreach (array_reverse($lift) as $element) {
		$stacks[$to][] = $element;
	}
	var_dump($stacks);
}

foreach ($stacks as $stack) {
	echo array_pop($stack);
}
