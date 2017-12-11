<?php 
/**
*	This file is part of the joomimart.csv library
*
*	@license http://opensource.org/licenses/MIT
*	@link https://github.com/joomimart/csv
*	@version 1.0.1
*	@package joomimart.csv
*
*	For the full copyright and license information, please view the LICENSE
*	file that was distributed with this source code.
*/
	require '/vendor/autoload.php';
	//	require_once '/vendor/joomimart/csv/src/Reader.php';
	//	require_once __DIR__.'/src/Reader.php';
	$filename = 'test.csv';
	$csv = new joomiMart\Csv\Reader($filename);
	
	$csv->setItemStart(10); // start item - default: 1
	$csv->setExecute(50); // amount - default: 0
	$records = $csv->Read();
	
	$item_start = $csv->getItemStart(); // returns 0
	$count = count($records);
	if ($count >= 1) {
		foreach ($records as $key => $item) {
		
		$real_key = $key + $item_start - 1;
		
			print_r('<br>');
			print_r($real_key);
			print_r('<br>');
			print_r($item);
			print_r('<br>');
		
		}
	}
  
