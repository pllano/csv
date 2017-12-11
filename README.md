# Pllano CSV Reader

[![Latest Version](https://img.shields.io/github/release/pllano/csv.svg?style=flat-square)](https://github.com/pllano/csv/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Read large data from csv files in parts in php

System Requirements
-------

You need **PHP >= 5.3*

Install
-------

Install `Csv Reader` using Composer.

```
$ composer require pllano/csv
```

or in composer.json

```
"require": {
	"pllano/csv": "1.*"
}
```

Fast start => tests/FastStart.php
-------

Save the above code fragment as `test.php` in your Web root folder.

``` php
require 'vendor/autoload.php';
//	require_once '/vendor/pllano/csv/src/Reader.php';
//	require_once __DIR__.'/src/Reader.php';

$filename = 'test.csv';

$csv = new Pllano\Csv\Reader($filename);

$records = $csv->Read();

$count = count($records);
if ($count >= 1) {
	foreach ($records as $item) {
			
		print_r($item);
		print_r('<br>');
		
	}
}
```

Read one line => tests/ReadOneLine.php
-------

``` php
require 'vendor/autoload.php';
//	require_once '/vendor/pllano/csv/src/Reader.php';
//	require_once __DIR__.'/src/Reader.php';

$filename = 'test.csv';

$csv = new Pllano\Csv\Reader($filename);

$csv->setItemStart(10);
$csv->setExecute(1);

$records = $csv->Read();

print_r($records);

/*
Array
(
	[0] => Array
	(
	[name] => Lorem
	[number] => 11
	[price] => 22.00
	)
)
*/

```

Real Line Key => tests/RealLineKey.php
-------

``` php
require 'vendor/autoload.php';
//	require_once '/vendor/pllano/csv/src/Reader.php';
//	require_once __DIR__.'/src/Reader.php';

$filename = 'test2.csv';

$csv = new Pllano\Csv\Reader($filename);
	
$csv->setItemStart(10); // start item - default: 1
$csv->setExecute(50); // amount - default: 0

$records = $csv->Read();
	
$item_start = $csv->getItemStart(); // returns 0

$count = count($records);
if ($count >= 1) {
	foreach ($records as $key => $item) {
		
	$real_key = $key + $item_start;
		
		print_r($real_key);
		print_r(' - ');
		print_r($item);
		print_r('<br>');
		
	}
}

```

Example => tests/ReadRefresh.php
-------

``` php
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
//	require_once '/vendor/pllano/csv/src/Reader.php';
//	require_once __DIR__.'/src/Reader.php';
	
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
		//	start //site.com/test.php
		print '<meta http-equiv="Refresh" content="0; url=/test.php?filename='.$filename.'&amp;start='.$end.'">';
	}

	if ($end >= $stop || $end >= $rows_total) {
		print_r('<br>');
		print_r('Memory, MB: '.$csv->getMemory());
		print_r('<br>');
		print_r('Time, sec: '.$csv->getTime());
	}
	
}
```

Security
-------

If you discover any security related issues, please email support@pllano.com instead of using the issue tracker.


License
-------

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
