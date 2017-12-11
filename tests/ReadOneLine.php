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
	//	Include Composer autoloader if not already done.
	require 'vendor/autoload.php';
	//	require_once '/vendor/joomimart/csv/src/Reader.php';
	//	require_once __DIR__.'/src/Reader.php';
	$filename = 'test.csv';
	$csv = new joomiMart\Csv\Reader($filename);
	$csv->setItemStart(10);
	$csv->setExecute(1);
	$records = $csv->Read();
	print_r($records);
  
