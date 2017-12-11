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
namespace Pllano\Csv;
use SplFileObject;

class Reader
{
	/**
		* @var string
	*/
	private $filename = null;
	
	
	/**
		* @var null|false|array Null - is not yet set, False - don't use headers, Array - already loaded headers
	*/
	private $headers = null;
	
	/**
		* @var array
	*/
	private $header = null;
	
	/**
		* @var resource
	*/
	protected $delimiter = ';';
	/**
		* @var resource
	*/
	
	protected $enclosure = '"';
	
	/**
		* @var string
	*/
	protected $escape = '\\';
	
	/**
		* @var string
	*/
	private $end = 100000;
		
	/**
		* @var string
	*/
	private $execute = 100000;
		
	/**
		* @var string
	*/
	private $item_start = 1;
		
	/**
		* @var string
	*/
	private $item_end = '';
		
	/**
		* @var string
	*/
	private $auto_detection = false;
		
	/**
		* @var string
	*/
	private $get_csv_control = null;
		
	/**
		* @var string
	*/
	private $time_limit = 29;
		
	/**
		* @var string
	*/
	private $get_memory = '';
		
	/**
		* @var string
	*/
	private $get_time = null;
	/**
		* @var string
	*/
	public function __construct($filename = null)
	{
		if ($filename !== null) {
			$this->filename = $filename;
		}
	}
		
	public function Read()
	{
		$original = ini_get("auto_detect_line_endings");
		ini_set('auto_detect_line_endings',true);
		$start = microtime(true);
		$startMemory = 0;
		$startMemory = memory_get_usage();
		if(!file_exists($this->filename) || !is_readable($this->filename)) {return false;}
			
		$file = new SplFileObject($this->filename, 'r');
			
		if ($file !== false) {
				
			if($this->auto_detection !== false){
				$file->seek(0);
				if (($detect = $file->fgets()) !== false) {
					$AutoDetection = $this->AutoDetection($detect);
					$this->delimiter = $AutoDetection;
				}
			}
				
			$file->setFlags(SplFileObject::READ_CSV);
			$file->setCsvControl($this->delimiter, $this->enclosure, $this->escape);
			$this->get_csv_control = $file->getCsvControl();
				
			if($this->headers) {
				$this->header = explode($this->delimiter, $this->headers);
			} else {
				$this->header = null;
			}
				
			if ($this->header == null) {
				$file->seek(0);
				if (($buffer = $file->fgets()) !== false) {
					$buffer = trim(str_replace($this->enclosure, '', $buffer));
					$this->file_temp_ftell = $file->key();
					$this->header = explode($this->delimiter, $buffer);
				}
			}
			$data = array();
			if($this->item_start == 1 && $this->execute == 1) {
				
				$file->seek(1);
				$this->end = 2;
				$data[] = array_combine($this->header, $file->current());
				
				$line_current = $file->key();
				$this->item_end = $line_current;
				$this->get_time = round((microtime(true) - $start), 2);
				$this->get_memory = (number_format(((memory_get_usage() - $startMemory) / 1000000), 2, '.', ''));
				ini_set("auto_detect_line_endings", $original);
				$file = null;
				return $data;
			}
			
			if($this->item_start >= 2 && $this->execute == 1) {
				
				$file->seek($this->item_start);
				$data[] = array_combine($this->header, $file->current());
				
				$line_current = $file->key();
				$this->item_end = $line_current;
				$this->get_time = round((microtime(true) - $start), 2);
				$this->get_memory = (number_format(((memory_get_usage() - $startMemory) / 1000000), 2, '.', ''));
				ini_set("auto_detect_line_endings", $original);
				$file = null;
				return $data;
			}
			
			if($this->item_start == 1 && $this->execute >= 2) {
			
				$file->seek(1);
				$data[] = array_combine($this->header, $file->current());
				
				$this->end = $this->item_start + $this->execute - 2;
				$file->seek($this->item_start);
				while (!$file->eof()) {
					$row = $file->fgetcsv();
					$data[] = array_combine($this->header, $row);
					$line_current = $file->key();
					$this->item_end = $line_current;
				
					if($line_current == $this->end || $file->valid() == FALSE || $this->get_time >= $this->time_limit) {
						$this->get_time = round((microtime(true) - $start), 2);
						$this->get_memory = (number_format(((memory_get_usage() - $startMemory) / 1000000), 2, '.', ''));
						ini_set("auto_detect_line_endings", $original);
						$file = null;
						return $data;
						
					}
				
				}
			
			}
			
			if($this->item_start >= 2 && $this->execute >= 2) {
			
				$this->end = $this->item_start + $this->execute - 1;
				$file->seek($this->item_start - 1);
				while (!$file->eof()) {
					
					$row = $file->fgetcsv();
					$data[] = array_combine($this->header, $row);
					$line_current = $file->key();
					$this->item_end = $line_current;
				
					if($line_current == $this->end || $file->valid() == FALSE || $this->get_time >= $this->time_limit) {
						$this->get_time = round((microtime(true) - $start), 2);
						$this->get_memory = (number_format(((memory_get_usage() - $startMemory) / 1000000), 2, '.', ''));
						ini_set("auto_detect_line_endings", $original);
						$file = null;
						return $data;
					}
				
				}
			
			}
		}
			
	}
	/**
	* @return array getCsvControl 
	*/
	public function getCsvControl()
	{
		if($this->get_csv_control !== null){
			$getCsvControl = $this->get_csv_control;
			return $getCsvControl;
		} else {
			return null;
		}
	}
	/**
	* @param string auto_detection null|true
	*/
	function AutoDetection($detect) 
	{
		$separators = [',',';','|'];
		$replace = preg_replace('/".+"/isU', '*', $detect);
		$DetectDelimiter;
		$i = -1;
			foreach($separators as $item) {
				if(($size = sizeof(explode($item, $replace))) > $i) {
					$i = $size;
					$DetectDelimiter = $item;
				}
			}
		return $DetectDelimiter;
	}
		
	/**
		* @param string auto_detection null|true
	*/
	public function setAutoDetection($status)
	{
		$this->auto_detection = $status;
	}
		
	/**
		* @return string getAutoDetection
	*/
	public function getAutoDetection()
	{
		if($this->auto_detection !== false){
			if (($file = fopen($this->filename, 'r')) !== false) {
				$detect = fgets($file);
				$AutoDetection = $this->AutoDetection($detect);
				return $AutoDetection;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
		
	/**
		* @return string countItems
	*/
	public function countItems()
	{
		if (fopen($this->filename, 'r')) {
			$line_count = count(file($this->filename));
			return $line_count;
		}
	}
		
	/**
		* @param string time_limit
	*/
	public function setTimeLimit($time_limit)
	{
		$this->time_limit = $time_limit;
	}
		
	/**
		* @param string item_start
	*/
	public function setItemStart($item_start)
	{
		$this->item_start = $item_start;
		$count = $this->countItems();
		if($item_start == null || $item_start == 0) {
			$this->item_start = 1;
		} 
		if($item_start >= $count) {
			$this->item_start = $count;
		}
		
	}
		
	/**
		* @return string item_start
	*/
	public function getItemStart()
	{
		return $this->item_start;
	}
		
	/**
		* @param string item_end
	*/
	public function setItemEnd($item_end)
	{
		$this->item_end = $item_end;
	}
		
	/**
		* @return string item_end
	*/
	public function getItemEnd()
	{
		return $this->item_end;
	}
		
	/**
		* @param string $execute
	*/
	public function setExecute($execute)
	{
		$this->execute = $execute;
		$count = $this->countItems();
		if($execute >= $count) {
			$this->execute = $count;
		}
		
	}
		
	/**
		* @return string execute
	*/
	public function getExecute()
	{
		return $this->execute;
	}
		
	/**
		* @param array|null $headers
	*/
	public function setHeaders($headers)
	{
		$this->headers = $headers;
	}
		
	/**
		* @return string headers
	*/
	public function getHeaders()
	{
		return $this->header;
	}
		
	/**
		* @param string $delimiter
	*/
	public function setDelimiter($delimiter)
	{
		$this->delimiter = $delimiter;
	}
		
	/**
		* @return string delimiter
	*/
	public function getDelimiter()
	{
		return $this->delimiter;
	}
		
	/**
		* @param string $enclosure
	*/
	public function setEnclosure($enclosure)
	{
		$this->enclosure = $enclosure;
	}
		
	/**
		* @return string enclosure
	*/
	public function getEnclosure()
	{
		return $this->enclosure;
	}
	/**
		* @param string $escape
	*/
	public function setEscape($escape)
	{
		$this->escape = $escape;
	}
		
	/**
		* @return string escape
	*/
	public function getEscape()
	{
		return $this->escape;
	}
		
	/**
		* @return string escape
	*/
	public function getMemory()
	{
		return $this->get_memory;
	}
		
	/**
		* @return string escape
	*/
	public function getTime()
	{
		return $this->get_time;
	}
}
