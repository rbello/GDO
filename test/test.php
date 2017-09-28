<?php

error_reporting(E_ALL);

abstract class Test {

	protected $name;
	protected $errors = 0;

	public function Test($name) {
		$this->name = $name;
	}
	
	public function assertNull($var, $label) {
		if ($var !== NULL) $this->echoError($label, array($var));
	}

	public function assertNotNull($var, $label) {
		if ($var === NULL) $this->echoError($label, array($var));
	}

	public function assertEquals($var, $value, $label) {
		//echo gettype($var)."($var) != ".gettype($value)."($value)<br />";
		if ($var !== $value) $this->echoError($label, array($var, $value, gettype($var), gettype($value)));
	}

	public function assertNotEquals($var, $value, $label) {
		if ($var === $value) $this->echoError($label, array($var, $value, gettype($var), gettype($value)));
	}

	public function assertTrue($var, $label) {
		if ($var !== TRUE) $this->echoError($label, array($var));
	}

	public function assertFalse($var, $label) {
		if ($var !== FALSE) $this->echoError($label, array($var));
	}

	public function echoError($msg, $vars=array()) {
		$this->errors++;
		$i = 1;
		foreach ($vars as $v) {
			$msg = str_replace("%$i", $v, $msg);
			$i++;
		}
		echo "\t<p style=\"color:red\">$msg</p>\n";
	}

	public function onSuccess($msg='Test Successful') {
		if ($this->errors == 0) echo "\t<p style=\"color:green\">$msg</p>\n";
	}

	public function getErrorCount() {
		return $this->errors;
	}

	public function start() {
		echo "\n<h2>Test &raquo; {$this->name}</h2>";
		echo "\n<div style=\"background:#eee\">";
		$this->run();
		echo '</div>';
	}

	public abstract function run();

}

class Test2 extends Test {

	public function __construct($name, $file, $checksum) {
		parent::__construct($name);
		$this->file = $file;
		$this->checksum = $checksum;
	}

	public function run() {
		echo '<pre>';
		echo htmlentities(file_get_contents($this->file));
		echo '</pre>';
		echo '<h3>Result : <img src="'.$this->file.'" /></h3>';
		include $this->file;
		echo '<p>toString : '.$img.' ('.get_class($img).')</p>';
		if (is_object($img)) {
			$sum = $img->getChecksum();
			$this->assertEquals($sum, $this->checksum, 'Checksum not matching (img:'.$sum.' != needle:'.$this->checksum.')');
		}
	}

}

?>