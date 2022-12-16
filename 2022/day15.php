<?php

$input = '
';


function isInRange($x, $y) {
	global $sensors, $beacons;
	foreach ($sensors as $k => $sensor) {
		$distance = abs($sensor['x'] - $x) + abs($sensor['y'] - $y);
		if ($distance <= $sensor['distance']) {
			if ($y > $sensor['y']) {
				return $sensor['distance'] - $distance;
			} else {
				return ($distance - abs($sensor['x'] - $x)) * 2 + 1;				
			}
		}
	}
	return false;
}

function outputMap() {
	global $xrange, $yrange, $sensors, $beacons;
	
	foreach (range($yrange[0], $yrange[1]) as $y) {
		foreach (range($xrange[0], $xrange[1]) as $x) {
			if (isset($sensors["$x-$y"])) {
				echo 'S';
			} else if (isset($beacons["$x-$y"])) {
				echo 'B';
			} else if (isInRange($x, $y)) {
				echo '#';
			} else {
				echo '.';
			}
		}
		echo "\n";
	}
}


$input = trim($input);
$input = explode("\n", $input);
$xrange = [INF, -INF];
$yrange = [INF, -INF];
$maxdistance = 0;
$sensors = [];
$beacons = [];
foreach ($input as $line) {
	preg_match_all('%-?\d+%', $line, $matches);
	list($sx, $sy, $bx, $by) = $matches[0];
	$xrange[0] = min($xrange[0], $sx, $bx);
	$xrange[1] = max($xrange[1], $sx, $bx);
	$yrange[0] = min($yrange[0], $sy, $by);
	$yrange[1] = max($yrange[1], $sy, $by);
	$sensors["$sx-$sy"] = [
		'x' => $sx,
		'y' => $sy,
		'distance' => abs($sx - $bx) + abs($sy - $by)
	];
	$maxdistance = max($maxdistance, abs($sx - $bx) + abs($sy - $by));
	$beacons["$bx-$by"] = [
		'x' => $bx,
		'y' => $by,
		'distance' => abs($sx - $bx) + abs($sy - $by)
	];
}

$xrange[0] -= $maxdistance;
$xrange[1] += $maxdistance;

//outputMap();

echo "\n";
$nobeacon = [];
$y = 2000000;
// $y = 10;
for ($x = $xrange[0]; $x <= $xrange[1]; ++$x) {
	if (isset($sensors["$x-$y"])) {
		$nobeacon[$x] = true;
	} else if (isset($beacons["$x-$y"])) {
	} else if (isInRange($x, $y) !== false) {
		$nobeacon[$x] = true;
	}
}
echo count($nobeacon);

echo "\n\n";

for ($y = 0; $y < 4000000; ++$y) {
	for ($x = 0; $x < 4000000; ++$x) {
		if (isset($sensors["$x-$y"])) {
			continue;
		} else if (isset($beacons["$x-$y"])) {
			continue;
		} else if (($skip = isInRange($x, $y)) !== false) {
			$x += $skip;
			continue;
		}
		echo "$y $x"\n";
		break 2;
	}
}



