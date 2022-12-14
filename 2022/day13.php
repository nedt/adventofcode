<?php

$input = '
';

$input = trim($input);
$input = explode("\n", $input);

function decodeLine($line) {
	return json_decode($line);
}

function comparePair($a, $b, $indent = "") {
	if (is_scalar($a) && is_scalar($b)) {
		echo "$indent $a <=> $b = ", $a <=> $b, "\n";
		return $a <=> $b; 
	}
	$a = (array)$a;
	$b = (array)$b;
	echo "$indent " . json_encode($a) . " <=> " . json_encode($b) . "\n";
	for ($i = 0; $i < min(count($a), count($b)); ++$i) {
		$compare = comparePair($a[$i], $b[$i], "$indent  ");
		if ($compare) {
			return $compare;
		}
	}
	echo "  $indent count(a) <=> count(b) = ", count($a) <=> count($b), "\n";
	return count($a) <=> count($b);
}

$pairs = [];
for($i = 0; $i < count($input); $i += 3) {
	$pairs[] = [
		decodeLine($input[$i]),
		decodeLine($input[$i + 1]),
	];
}

$sum = 0;
foreach ($pairs as $k => $pair) {
	echo "Pair $k\n";
	if (comparePair($pair[0], $pair[1]) < 0) {
		echo $k + 1, "\n";
		$sum += $k + 1;
	}
}
echo $sum;

echo "\n\n";
$all = [
	'[[2]]' => decodeLine('[[2]]'),
	'[[6]]' => decodeLine('[[6]]'),
];
foreach ($input as $line) {
	if (!trim($line)) {
		continue;
	}
	$all[$line] = decodeLine($line);
}

uasort($all, 'comparePair');
$packets = array_keys($all);
var_dump($packets);
echo (array_search('[[2]]', $packets) + 1) * (array_search('[[6]]', $packets) + 1);
