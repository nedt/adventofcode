<?php

$input = '
';

$input = trim($input);
$map = array_map(function ($line) {
	return str_split($line);
}, explode("\n", $input));


var_dump($map);

function getLevel($map, $pos) {
	$level = $map[$pos[0]][$pos[1]];
	if ($level == 'E') {
		$level = 'z';
	}
	if ($level == 'S') {
		$level = 'a';
	}
	return ord($level) - ord('a');
}

function isGoodCandidate($level, $newLevel, $forward = true) {
	if ($level == $newLevel) {
		return true;
	}
	if ($forward) {
		if ($newLevel < $level) {
			return true;
		}
		if (($newLevel - 1) == $level) {
			return true;
		}
	} else {
		if ($newLevel > $level) {
			return true;
		}
		if (($newLevel + 1) == $level) {
			return true;
		}		
	}
	return false;
}

function getCandidates($map, $pos, $forward = true) {
	$candidates = [];
	
	if ($pos[0] > 0) {
		$newPos = [$pos[0] - 1, $pos[1]];
		if (isGoodCandidate(getLevel($map, $pos), getLevel($map, $newPos), $forward)) {
			$candidates[] = $newPos;
		}
	}
	if ($pos[1] > 0) {
		$newPos = [$pos[0], $pos[1] - 1];
		if (isGoodCandidate(getLevel($map, $pos), getLevel($map, $newPos), $forward)) {
			$candidates[] = $newPos;
		}
	}
	if ($pos[0] < count($map) - 1) {
		$newPos = [$pos[0] + 1, $pos[1]];
		if (isGoodCandidate(getLevel($map, $pos), getLevel($map, $newPos), $forward)) {
			$candidates[] = $newPos;
		}
	}
	if ($pos[1] < count($map[0]) - 1) {
		$newPos = [$pos[0], $pos[1] + 1];
		if (isGoodCandidate(getLevel($map, $pos), getLevel($map, $newPos), $forward)) {
			$candidates[] = $newPos;
		}
	}
	
	return $candidates;
}


	
function getPos($map, $char = 'S') {
	$end = [];
	foreach ($map as $k0 => $l) {
		foreach ($l as $k1 => $v) {
			if ($v == $char) {
				return [$k0, $k1];
			}
		}
	}	
}
	
function getPath($tree, $current) {
	$current = implode('-', $current);
	$path = [$current];
	while (isset($tree[$current])) {
		$current = $tree[$current];
		$path[] = $current;
	}
	return $path;
}

function findPath($map, $forward = true, $start = null, $alsoEndAt = '') {
	if ($start == null) {
		$start = getPos($map, $forward ? 'S' : 'E');		
	}
	$queue = [ $start ];
	$tree = [];
	$visited[implode('-', $start)] = 1;
	while ($queue) {
		$current = array_shift($queue);
		if ($map[$current[0]][$current[1]] == ($forward ? 'E' : 'S')) {
			return getPath($tree, $current);
		}
		if ($alsoEndAt && $map[$current[0]][$current[1]] == $alsoEndAt) {
			return getPath($tree, $current);
		}
		foreach (getCandidates($map, $current, $forward) as $next) {
			if (empty($visited[implode('-', $next)])) {
				$visited[implode('-', $next)] = 1;
				$tree[implode('-', $next)] = implode('-', $current);
				$queue[] = $next;
			}
		}
	}


}

$path = findPath($map);
echo count($path), ': ', implode(' > ', $path), "\n";
echo "\n\n";

foreach ($map as $k0 => $l) {
	foreach ($l as $k1 => $v) {
		if ($v == 'a') {
			$path = findPath($map, true, [$k0, $k1]);
			if ($path) {
				echo count($path), ': ', implode(' > ', $path), "\n";				
			}
		}
	}
}	

echo "\n\n";
$path = findPath($map, false, null, 'a');
echo count($path), ': ', implode(' > ', $path), "\n";




