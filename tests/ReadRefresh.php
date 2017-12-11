<?php 
/**
*	This file is part of the pllano.csv library
*
*	@license http://opensource.org/licenses/MIT
*	@link https://github.com/pllano/csv
*	@version 1.0.2
*	@package pllano.csv
*
*	For the full copyright and license information, please view the LICENSE
*	file that was distributed with this source code.
*/
function clean($value = '')
{
	$value = trim($value);
	$value = stripslashes($value);
	$value = strip_tags($value);
	$value = htmlspecialchars($value, ENT_QUOTES);
	//	$value = htmlentities($value);
	return $value;
}
	$filename = 'test.csv';
	$start = 0;
	$rows_total = 0;
	
	if ($_GET["filename"]) {$filename = clean($_GET["filename"]);}
	if ($_GET["start"]) {$start = clean($_GET['start']);}
	
	//	Include Composer autoloader if not already done.
	require 'vendor/autoload.php';
	//	require_once __DIR__.'/vendor/pllano/csv/src/Reader.php';
	
	$csv = new Pllano\Csv\Reader($filename);
	
	//	$csv->setDelimiter(';'); // default: ;
	//	$csv->setEnclosure('"'); // default: "
	//	$csv->setEscape('\\'); // default: \\
	//	$csv->setHeaders('name;number;price'); // default: null
	$csv->setItemStart($start); // start item - default: 0
	$csv->setExecute(10); // amount - default: 0
	//	$csv->setTimeLimit(29); // Monitoring the execution time of the script in seconds set_time_limit
	//	$csv->setAutoDetection(false); // Auto Detection Delimiter false|true - default: false
	
	$stop = 500;
		$records = $csv->Read();
		
		$count = count($records);
		
		if ($count >= 1) {
			foreach ($records as $item) {
			
				print_r($item);
				print_r('<br>');
		
			}
		}
	
	$rows_total = $csv->countItems(); // returns total items
	//	$csv->getHeaders(); // returns Array ( [0] => name [1] => number [2] => price )
	//	$csv->getItemStart(); // returns string
	//	$csv->getExecute(); // returns amount 10
	$end = $csv->getItemEnd(); // returns 11
	//	$csv->getAutoDetection(); // returns false|true
	//	$csv->getCsvControl(); // returns Array ( [0] => ; [1] => " [2] => \ )
	if ($filename && $end >= 0 && $rows_total >= 0) {
	
		if ($end <= $rows_total && $end <= $stop) {
			//	start https://site.com/test.php
			print '<meta http-equiv="Refresh" content="0; url=/test.php?filename='.$filename.'&amp;start='.$end.'">';
		}
		if ($end >= $stop || $end >= $rows_total) {
			print_r('<br>');
			print_r('Memory, MB: '.$csv->getMemory());
			print_r('<br>');
			print_r('Time, sec: '.$csv->getTime());
		}
		
	}
