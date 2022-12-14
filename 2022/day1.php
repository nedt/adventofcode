<?php

$input = '
';

$input = trim($input);
$input = explode("\n\n", $input);
$sums = [];
foreach ($input as $elf) {
	$calories = explode("\n", $elf);
	$sum = array_sum($calories);
	$sums[] = $sum;
}
sort($sums);
echo array_pop($sums) + array_pop($sums) + array_pop($sums);
