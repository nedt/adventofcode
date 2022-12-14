<?php


$input = '
';


function getMapOutput($map, $xrange, $yrange) {
	$output = '';
	foreach (range($xrange[0], $xrange[1]) as $x) {
		foreach (range($yrange[0], $yrange[1]) as $y) {
			if (empty($map[$x][$y])) {
				$output .= '.';
			} else {
				$output .= $map[$x][$y];
			}
		}
		$output .= "\n";
	}
	return $output;
}

$input = trim($input);
$input = explode("\n", $input);
$map = [];
$xrange = [0, 0];
$yrange = [INF, 0];
foreach ($input as $line) {
	$line = explode(' -> ', $line);
	$from = current($line);
	$from = explode(',', $from);
	while ($to = next($line)) {
		$to = explode(',', $to);
		$xrange[0] = min($xrange[0], $from[1], $to[1]);
		$xrange[1] = max($xrange[1], $from[1], $to[1]);
		foreach (range($from[1], $to[1]) as $x) {
			if (!isset($map[$x])) {
				$map[$x] = [];
			}
			$yrange[0] = min($yrange[0], $from[0], $to[0]);
			$yrange[1] = max($yrange[1], $from[0], $to[0]);
			foreach (range($from[0], $to[0]) as $y) {
				$map[$x][$y] = '#';				
			}			
		}
		
		$from = $to;
	}
}
++$xrange[1];
--$yrange[0];
++$yrange[1];

echo getMapOutput($map, $xrange, $yrange);

$units = 0;
while (true) {
	$pos = [0, 500];
	while ($pos[0] < $xrange[1]) {
		++$pos[0];
		if (!empty($map[$pos[0]][$pos[1]])) {
			if (empty($map[$pos[0]][$pos[1] - 1])) {
				--$pos[1];
			} else if (empty($map[$pos[0]][$pos[1] + 1])) {
				++$pos[1];
			} else {
				--$pos[0];
				break;
			}
		}
	}
	if ($pos[0] >= $xrange[1]) {
		// break; // make or break part 1 or 2
	}
	$yrange[0] = min($yrange[0], $pos[1]);
	$yrange[1] = max($yrange[1], $pos[1]);
	++$units;
	$map[$pos[0]][$pos[1]] = 'o';
	$output = getMapOutput($map, $xrange, $yrange);
	echo "\e[H\e[J\n$output";
	usleep(1000);

	if ($pos[0] === 0) {
		break;
	}	
}

// showMap($map, $xrange, $yrange);
echo $units;
