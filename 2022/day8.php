<?php

$input = '
';

function getGrid($trees, $columns) {
	$grid = '';
	foreach ($trees as $k => $tree) {
		if ($k && $k % $columns === 0) {
			$grid .= "\n";
		}
		$grid .= !empty($tree['visible']) ? $tree['height'] : '.';
	}
	return $grid;
}

function getScenicGrid($trees, $columns) {
	$grid = '';
	$max = max(...array_map(function ($tree) { return $tree['scenic']; }, $trees));
	$fact = $max < 36 ? 1 : (35 / $max);
	foreach ($trees as $k => $tree) {
		if ($k && $k % $columns === 0) {
			$grid .= "\n";
		}
		$score = floor($tree['scenic'] * $fact);
		$score = base_convert($score, 10, 36);
		
		
		$grid .= $score;
	}
	return $grid;
}


function getScenicScore($trees, $i, $j, $rows, $columns) {
	$scores = [];
	$pos = $i * $columns + $j;	
	$stopAt = $trees[$pos]['height'];

	$score = 0;
	for ($ii = $i + 1; $ii < $rows; ++$ii) {
		$pos = $ii * $columns + $j;	
		++$score;
		if ($trees[$pos]['height'] >= $stopAt) {
			break;
		}
	}
	$scores[] = $score;
	
	$score = 0;
	for ($ii = $i - 1; $ii >= 0; --$ii) {
		$pos = $ii * $columns + $j;	
		++$score;
		if ($trees[$pos]['height'] >= $stopAt) {
			break;
		}
	}
	$scores[] = $score;

	$score = 0;
	for ($jj = $j + 1; $jj < $columns; ++$jj) {
		$pos = $i * $columns + $jj;	
		++$score;
		if ($trees[$pos]['height'] >= $stopAt) {
			break;
		}
	}
	$scores[] = $score;
	
	$score = 0;
	for ($jj = $j - 1; $jj >= 0; --$jj) {
		$pos = $i * $columns + $jj;	
		++$score;
		if ($trees[$pos]['height'] >= $stopAt) {
			break;
		}
	}
	$scores[] = $score;
	
	$total = $scores[0] * $scores[1] * $scores[2] * $scores[3];
	echo "$i $j: {$trees[$i * $columns + $j]['height']} $total = ", implode(' * ', $scores), "\n";

	return $total;
}

$input = explode("\n", trim($input));
$rows = count($input);
$columns = strlen($input[0]);
$trees = [];
foreach ($input as $line) {
	$line = array_map(function ($height) {
		return ['height' => $height];
	}, str_split($line));
	$trees = array_merge($trees, $line);
}


for ($i = 0; $i < $rows; ++$i) {
	$previous = -1;
	for ($j = 0; $j < $columns; ++$j) {
		$pos = $i * $columns + $j;
		$trees[$pos]['scenic'] = getScenicScore($trees, $i, $j, $rows, $columns);
		if ($trees[$pos]['height'] <= $previous) {
			continue;
		}
		$trees[$pos]['visible'] = true;
		$previous = $trees[$pos]['height'];
	}
}

for ($i = 0; $i < $rows; ++$i) {
	$previous = -1;
	for ($j = $columns - 1; $j >= 0; --$j) {
		$pos = $i * $columns + $j;
		if ($trees[$pos]['height'] <= $previous) {
			continue;
		}
		$trees[$pos]['visible'] = true;
		$previous = $trees[$pos]['height'];
	}
}

for ($j = 0; $j < $columns; ++$j) {
	$previous = -1;
	for ($i = 0; $i < $rows; ++$i) {
		$pos = $i * $columns + $j;
		if ($trees[$pos]['height'] <= $previous) {
			continue;
		}
		$trees[$pos]['visible'] = true;
		$previous = $trees[$pos]['height'];
	}
}

for ($j = 0; $j < $columns; ++$j) {
	$previous = -1;
	for ($i = $rows - 1; $i >= 0; --$i) {
		$pos = $i * $columns + $j;
		if ($trees[$pos]['height'] <= $previous) {
			continue;
		}
		$trees[$pos]['visible'] = true;
		$previous = $trees[$pos]['height'];
	}
}


echo getGrid($trees, $columns), "\n";
echo $rows * $columns - substr_count(getGrid($trees, $columns), '.'), "\n";

echo getScenicGrid($trees, $columns), "\n";
echo max(...array_map(function ($tree) { return $tree['scenic']; }, $trees));
