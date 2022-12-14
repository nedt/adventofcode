<?php

$input = '
';

$head = [0, 0];
// $tail = [0, 0];
$tails = [];
for ($i = 0; $i < 9; ++$i) {
	$tails[$i] = $head;
}
$visited = [];

function getGrid($max, $head, $tails, $visited) {
	$output = '';
	for ($x = $max - 1; $x >= -$max; --$x) {
		for ($y = -$max; $y < $max; ++$y) {
			if ($head[0] == $y && $head[1] == $x) {
				$output .= 'H';
				continue;
			}
			foreach ($tails as $k => $tail) {
				if ($tail[0] == $y && $tail[1] == $x) {
					$output .= $k + 1;
					continue 2;
				}				
			}
			if ($x == 0 && $y == 0) {
				$output .= 's';
			} else if (!empty($visited["$y-$x"])) {
				$output .= '*';
			} else {
				$output .= '.';
			}
		}
		$output .= "\n";
	}
	return $output;
}

function followPosition($parent, $child) {
	$d0 = $parent[0] - $child[0];
	$d1 = $parent[1] - $child[1];
	if ($d0 === 0 && abs($d1) > 1) {
		$child[1] += $d1 > 0 ? 1 : -1;
	} else if ($d1 === 0 && abs($d0) > 1) {
		$child[0] += $d0 > 0 ? 1 : -1;			
	} else if (abs($d0) > 1) {
		$child[0] += $d0 > 0 ? 1 : -1;			
		$child[1] += $d1 > 0 ? 1 : -1;			
	} else if (abs($d1) > 1) {
		$child[0] += $d0 > 0 ? 1 : -1;			
		$child[1] += $d1 > 0 ? 1 : -1;						
	}
	return $child;
}

$input = trim($input);
$input = explode("\n", $input);
foreach ($input as $line) {
	[$direction, $steps] = explode(' ', $line);
	for ($i = 0; $i < $steps; ++$i) {
		switch ($direction) {
			case 'U':
				++$head[1];
				break;
			case 'D':
				--$head[1];
				break;
			case 'L':
				--$head[0];
				break;
			case 'R':
				++$head[0];
				break;
		}
		foreach ($tails as $k => $tail) {
			$tails[$k] = followPosition($k ? $tails[$k - 1] : $head, $tail);
		}
		$visited[implode('-', $tails[count($tails) - 1])] = 1;
		$grid = getGrid(60, $head, $tails, $visited);
		echo "\e[H\e[J\n$grid";
		usleep(10000);
	}
}
var_dump($visited);
echo count($visited);
