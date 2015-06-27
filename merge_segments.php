<?php

define('THRESHOLD', .4);


if (!isset($argv[1]) || !isset($argv[2])) {
	echo <<<ENDL

Usage:

	php merge_segments.php <inp_dir> <out_dir>

	inp_dir: input files directory (.rttm)
	out_dir: output files directory



ENDL;
	exit;
}

$inDir = $argv[1];
$outDir = $argv[2];

$files = explode("\n", `ls $inDir`);
foreach ($files as $file) {
	if (strpos($file, '.rttm') === FALSE)
		continue;
	echo "Processing $file\n";
	file_put_contents("$outDir/$file", merge("$inDir/$file"));
}
echo "Done.\n\n";

function merge($file) {
	$lines = explode("\n", file_get_contents($file));
	foreach ($lines as $i => $line) {
		$p = explode(' ', $line);
		if (isset($p[4]) && is_numeric($p[4]) && $p[4] < THRESHOLD) {
			$lines[$i - 1][4] = number_format($lines[$i - 1][4] + $p[4], 3);
			unset($lines[$i]);
			continue;
		}
		$lines[$i] = $p;
	}

	foreach ($lines as $i => $line) {
		$lines[$i] = implode(' ', $line);
	}
	implode("\n", $lines);

	return $lines;;
}
