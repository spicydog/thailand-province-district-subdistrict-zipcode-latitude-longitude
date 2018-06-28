<?php

$csv = 'latlon.csv';

$latlons = [];

function cleanUp($str) {
	$chars = '่้็ั๊ี-ะเแใไาํำ';
	$chars = preg_split('//u', $chars, -1, PREG_SPLIT_NO_EMPTY);
	foreach ($chars as $char) {
		$str = str_replace($char, '', $str);
	}
	return $str;
}

if (($handle = fopen($csv, "r")) !== false) {
	while (($record = fgetcsv($handle, 0, ",")) !== false) {
		$cs = cleanUp($record[0]);
		$cd = cleanUp($record[1]);
		$cp = cleanUp($record[2]);
		if (! isset($latlons[$cp])) {
			$latlons[$cp] = [];
		}
		if (! isset($latlons[$cp][$cd])) {
			$latlons[$cp][$cd] = [];
		}
		if (! isset($latlons[$cp][$cd][$cs])) {
			$latlons[$cp][$cd][$cs] = [$record[3],$record[4]];
		}
	}
	fclose($handle);
}

$match = 0;
$miss = 0 ;
$csv = 'zipcode.csv';
if (($handle = fopen($csv, "r")) !== false) {
	while (($record = fgetcsv($handle, 0, ",")) !== false) {

		$cp = cleanUp($record[0]);
		$cd = cleanUp($record[1]);
		$cs = cleanUp($record[2]);


		if (isset($latlons[$cp][$cd][$cs])) {
			$latlon = $latlons[$cp][$cd][$cs];
			$match++;
			echo "$record[0],$record[1],$record[2],$record[3],$latlon[0],$latlon[1]\n";
		} else {
			$miss++;
			echo "$record[0],$record[1],$record[2],$record[3],null,null\n";
		}
	}
	fclose($handle);
}

// echo $miss . "/" . ($match+$miss);
