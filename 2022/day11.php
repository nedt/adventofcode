<?php


$input = '
';

$input = trim($input);
$input = explode("\n\n", $input);
$monkeys = [];
$inspected = [];
$mod = 1;
foreach ($input as $lines) {
	$lines = explode("\n", $lines);
	$monkey = [
		'to' => []
	];
	
	$values = explode(':', $lines[1]);
	$monkey['items'] = explode(',', str_replace(' ', '', $values[1]));

	$values = explode(':', $lines[2]);
	$monkey['operation'] = str_replace(['old', 'new'], ['$old', '$new'], $values[1]);
	
	$values = explode(' ', trim($lines[3]));
	$monkey['test'] = $values[3];
	$mod *= $monkey['test'];
	
	$values = explode(' ', trim($lines[4]));
	$monkey['to'][1] = $values[5];
	
	$values = explode(' ', trim($lines[5]));
	$monkey['to'][0] = $values[5];

	$monkeys[] = $monkey;
	$inspected[] = 0;
}

function calcValue($old, $operation) {
	eval($operation . ';');
	return $new;
}

for ($round = 1; $round <= 10000; ++$round) {
	foreach ($monkeys as $number => &$monkey) {
		while (!empty($monkey['items'])) {
			++$inspected[$number];
			$item = array_shift($monkey['items']);
			$value = calcValue($item, $monkey['operation']);
//			$value = floor($value / 3);
			$value = $value % $mod;
			$to = $monkey['to'][($value % $monkey['test']) ? 0 : 1];
			$monkeys[$to]['items'][] = $value;
			echo "$item->$value => $to\n";			
		}
	}
	unset($monkey);
	
	echo "Round $round\n";
	foreach ($monkeys as $key => $monkey) {
		echo "Monkey $key: ", implode(', ', $monkey['items']), "\n";
	}
	echo "\n";
	foreach ($inspected as $k => $v) {
		echo "Monkey $k inspected $v\n";
	}
	echo "\n";
	echo "\n";
}


var_dump($inspected);
