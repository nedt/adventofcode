<?php

$input = '
';

$count = 14;
$regex = '';
$backref = '';
for ($i = 2; $i < $count; ++$i) {
	$backref .= $backref ? "|\\$i" : "\\$i";
	$regex .= "(.)(?!$backref)";
}
$backref .= "|\\$count";
$regex = "%(?<before>.*?$regex(.))(?!$backref)(?<marker>.)%";


$input = trim($input);
$input = explode("\n", $input);
echo $regex, "\n";
foreach ($input as $line) {
//	preg_match('%(?<before>.*?(.)(?!\2)(.)(?!\2|\3)(.))(?!\2|\3|\4)(?<marker>.)%', $line, $matches);
	preg_match($regex, $line, $matches);
	echo strlen($matches['before']) + 1, "\n";
}

