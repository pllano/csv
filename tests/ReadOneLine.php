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
	//	Include Composer autoloader if not already done.
	require 'vendor/autoload.php';
	//	require_once '/vendor/pllano/csv/src/Reader.php';
	//	require_once __DIR__.'/src/Reader.php';
	$filename = 'test.csv';
	$csv = new Pllano\Csv\Reader($filename);
	$csv->setItemStart(10);
	$csv->setExecute(1);
	$records = $csv->Read();
	print_r($records);
  
