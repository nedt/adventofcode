<?php

$input = '
';

$input = trim($input);
$input = explode("\n", $input);

$x = 1;
$cycle = 0;
$time = [];

function drawScreen($cycle, $x) {
	$y = $cycle % 40;
	if ($y - $x >= 0 && $y - $x < 3) {
		echo '🅾️';
	} else {
		echo '❕';
	}
	if ($y == 0) {
		echo "\n";
	}	
}


++$cycle;
$time[$cycle] = $x;
drawScreen($cycle, $x);

foreach ($input as $line) {
	$line = explode(' ', $line);
	switch ($line[0]) {
		case 'noop':
			++$cycle;
			$time[$cycle] = $x;
			drawScreen($cycle, $x);
			break;
		case 'addx':
			++$cycle;
			$time[$cycle] = $x;
			drawScreen($cycle, $x);

			++$cycle;
			$x += $line[1];
			$time[$cycle] = $x;
			drawScreen($cycle, $x);

			break;
	}
}

$sum = 0;
for($i = 20; $i < count($time); $i += 40) {
	echo "$i {$time[$i]}\n";
	$sum += $i * $time[$i];
}
echo $sum;
