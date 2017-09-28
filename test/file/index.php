<?php

error_reporting(E_ALL);

require '../test.php';
require_once '../../src/evolya.gdo.php';

define('DO_NOT_RENDER', TRUE);

class FileTest extends Test {
	private $path;
	private $values;
	public function FileTest($path, $values) {
		parent::Test('Path solver test : '.$path);
		$this->path = $path;
		$this->values = $values;
	}
	public function run() {
		$file = new DFile($this->path);
		echo '<li>getName = '.$file->getName().'</li>';
		$this->assertEquals($file->getName(), $this->values[0], '$file->getName()');
		echo '<li>getPath = '.$file->getPath().'</li>';
		echo '<li>getAbsolutePath = '.$file->getAbsolutePath().'</li>';
		$this->assertEquals($file->getAbsolutePath(), $this->values[1], '$file->getAbsolutePath()');
		echo '<li>getParent = '.$file->getParent().'</li>';
		$this->assertEquals($file->getParent().'', $this->values[2], '$file->getParent()');
		$this->onSuccess();
	}
}

class FileTest2 extends Test {
	private $path;
	private $values;
	public function FileTest2($path, $values) {
		parent::Test('Exists getters test : '.$path);
		$this->path = $path;
		$this->values = $values;
	}
	public function run() {
		$file = new DFile($this->path);
		$this->assertEquals($file->exists(), $this->values[0], '$file->exists()');
		$this->assertEquals($file->isFile(), $this->values[1], '$file->isFile()');
		$this->assertEquals($file->isDirectory(), $this->values[2], '$file->isDirectory()');
		$this->onSuccess();
	}
}

class FileTest3 extends Test {
	private $parent;
	private $path;
	private $values;
	public function FileTest3($parent, $path, $values) {
		parent::Test('Constructor with parent test : '.$parent.' / '.$path);
		$this->parent = $parent;
		$this->path = $path;
		$this->values = $values;
	}
	public function run() {
		$file = new DFile(new DFile($this->parent), $this->path);
		echo '<li>getName = '.$file->getName().'</li>';
		$this->assertEquals($file->getName(), $this->values[0], '$file->getName()');
		echo '<li>getPath = '.$file->getPath().'</li>';
		echo '<li>getAbsolutePath = '.$file->getAbsolutePath().'</li>';
		$this->assertEquals($file->getAbsolutePath(), $this->values[1], '$file->getAbsolutePath()');
		echo '<li>getParent = '.$file->getParent().'</li>';
		$this->assertEquals($file->getParent().'', $this->values[2], '$file->getParent()');
		
		$f = new DFile($this->parent.'/'.$this->path);
		$this->assertTrue($file->equals($f), "Equals ($f != $file)");
		
		$this->onSuccess();
	}
}

class FileTest4 extends Test {
	public function FileTest4() {
		parent::Test('make/delete test');
	}
	public function run() {
		$file = new DFile('.', 'newfolder');
		$this->assertEquals($file->getAbsolutePath(), 'E:/work/Web/DEV/GDO/test/file/newfolder', '$file->getAbsolutePath()');
		echo '<p>Create folder</p>';
		$this->assertTrue($file->mkdir(), '$file->mkdir()');
		$this->assertTrue($file->isDirectory(), 'Directory should exists');
		echo '<p>Delete folder</p>';
		$this->assertTrue($file->delete(), '$file->delete()');
		$this->onSuccess();
	}
}

class FileTest5 extends Test {
	public function FileTest5 () {
		parent::Test('make/delete RECURSIVE test');
	}
	public function run() {
		$file = new DFile('./newfolder/test');
		$this->assertEquals($file->getAbsolutePath(), 'E:/work/Web/DEV/GDO/test/file/newfolder/test', '$file->getAbsolutePath() : %1 != %2');
		echo '<p>Create folder</p>';
		$this->assertTrue($file->mkdirs(), '$file->mkdirs()');
		$this->assertTrue($file->isDirectory(), 'Directory should exists');
		echo '<p>Delete folder</p>';
		$this->assertTrue($file->getParent()->delete(), '$file->delete()');
		$this->onSuccess();
	}
}

class FileTest6 extends Test {
	public function FileTest6 () {
		parent::Test('Create file test');
	}
	public function run() {
		$folder = new DFile('a/b/');
		$this->assertEquals($folder->getAbsolutePath(), 'E:/work/Web/DEV/GDO/test/file/a/b', '$folder->getAbsolutePath() : %1 != %2');
		echo '<p>Create folder</p>';
		$this->assertTrue($folder->mkdirs(), '$folder->mkdirs()');
		$this->assertTrue($folder->isDirectory(), 'Directory should exists');
		
		$file = new DFile($folder, 'c.txt');
		$this->assertEquals($file->getAbsolutePath(), 'E:/work/Web/DEV/GDO/test/file/a/b/c.txt', '$file->getAbsolutePath() : %1 != %2');
		$this->assertTrue($file->createNewDFile(), '$file->createNewDFile()');
		$this->assertTrue($file->isFile(), '$file->isFile()');
		$this->assertEquals($file->getSize(), 0, '$file->getSize()');
		$this->assertEquals($file->getLastModified(), time(), '$file->getLastModified()');
		echo '<p>OwnerID/OwnerName/Mode : '.$file->getOwnerID().'/'.$file->getOwnerName().'/'.$file->getPermsString().'</p>';
		
		
		echo '<p>Delete folder</p>';
		$this->assertTrue($folder->getParent()->delete(), '$file->delete()');
		
		$this->onSuccess();
	}
}

class FileTest7 extends Test {
	private $path;
	public function FileTest7($path) {
		parent::Test('List function test : '.$path);
		$this->path = $path;
	}
	public function run() {
		$folder = new DFile($this->path);
		$list = $folder->folderList();
		if ($list == NULL) {
			echo '<p>Found 0 sub files in '.$folder->getAbsolutePath().'</p>';
		}
		else {
			echo '<p>Found '.sizeof($list).' sub files in '.$folder->getAbsolutePath().'</p>';
		}
	}
}

?>
<html>
<head>
 <title>GDO : Tests : Class DFile</title>
 <style>
 body { margin: 0; }
 #top { background: #eee; font-size: 150%; padding: 20px; }
 #content { margin: 40px; }
 </style>
</head>
<body>

<div id="top">
GDO : <a href="../index.php">Tests</a>
: <a href="index.php">Class DFile</a>
</div>

<div id="content">
<?php

$test = new FileTest('.', array(
	'file',
	'E:/work/Web/DEV/GDO/test/file',
	'E:/work/Web/DEV/GDO/test'
));
$test->start();

$test = new FileTest('E:/work/Web/DEV/GDO/test/file', array(
	'file',
	'E:/work/Web/DEV/GDO/test/file',
	'E:/work/Web/DEV/GDO/test'
));
$test->start();

$test = new FileTest('E:/work/Web/DEV/GDO/test/file/bidule', array(
	'bidule',
	'E:/work/Web/DEV/GDO/test/file/bidule',
	'E:/work/Web/DEV/GDO/test/file'
));
$test->start();

$test = new FileTest('E:/work/Web/DEV/GDO/test/file/bidule/test.jpg', array(
	'test.jpg',
	'E:/work/Web/DEV/GDO/test/file/bidule/test.jpg',
	'E:/work/Web/DEV/GDO/test/file/bidule'
));
$test->start();

$test = new FileTest('E:/work/Web/DEV/GDO/test/file/test.jpg', array(
	'test.jpg',
	'E:/work/Web/DEV/GDO/test/file/test.jpg',
	'E:/work/Web/DEV/GDO/test/file'
));
$test->start();

$test = new FileTest('bidule/chouette', array(
	'chouette',
	'E:/work/Web/DEV/GDO/test/file/bidule/chouette',
	'E:/work/Web/DEV/GDO/test/file/bidule'
));
$test->start();

$test = new FileTest('bidule///chouette', array(
	'chouette',
	'E:/work/Web/DEV/GDO/test/file/bidule/chouette',
	'E:/work/Web/DEV/GDO/test/file/bidule'
));
$test->start();

$test = new FileTest('test.jpg', array(
	'test.jpg',
	'E:/work/Web/DEV/GDO/test/file/test.jpg',
	'E:/work/Web/DEV/GDO/test/file'
));
$test->start();

$test = new FileTest('/test.jpg', array(
	'test.jpg',
	'E:/work/Web/DEV/GDO/test/file/test.jpg',
	'E:/work/Web/DEV/GDO/test/file'
));
$test->start();

$test = new FileTest('./newfolder/test', array(
	'test',
	'E:/work/Web/DEV/GDO/test/file/newfolder/test',
	'E:/work/Web/DEV/GDO/test/file/newfolder'
));
$test->start();

$test = new FileTest('..', array(
	'test',
	'E:/work/Web/DEV/GDO/test',
	'E:/work/Web/DEV/GDO'
));
$test->start();

$test = new FileTest('../', array(
	'test',
	'E:/work/Web/DEV/GDO/test',
	'E:/work/Web/DEV/GDO'
));
$test->start();

$test = new FileTest('../..', array(
	'GDO',
	'E:/work/Web/DEV/GDO',
	'E:/work/Web/DEV'
));
$test->start();

$test = new FileTest('../../', array(
	'GDO',
	'E:/work/Web/DEV/GDO',
	'E:/work/Web/DEV'
));
$test->start();

$test = new FileTest('/', array(
	'E:',
	'E:',
	'E:'
));
$test->start();

$test = new FileTest('/.', array(
	'E:',
	'E:',
	'E:'
));
$test->start();

$test = new FileTest('/../../..', array(
	'E:',
	'E:',
	'E:'
));
$test->start();

$test = new FileTest('../../test.jpg', array(
	'test.jpg',
	'E:/work/Web/DEV/GDO/test.jpg',
	'E:/work/Web/DEV/GDO'
));
$test->start();

$test = new FileTest('/work/Web/DEV/Evolya3/SRC/gdo/', array(
	'gdo',
	'E:/work/Web/DEV/Evolya3/SRC/gdo',
	'E:/work/Web/DEV/Evolya3/SRC'
));
$test->start();

$test = new FileTest('/work/Web/DEV//Evolya3/SRC///gdo/', array(
	'gdo',
	'E:/work/Web/DEV/Evolya3/SRC/gdo',
	'E:/work/Web/DEV/Evolya3/SRC'
));
$test->start();

$test = new FileTest('/work/Web/DEV/Evolya3/SRC/gdo', array(
	'gdo',
	'E:/work/Web/DEV/Evolya3/SRC/gdo',
	'E:/work/Web/DEV/Evolya3/SRC'
));
$test->start();

$test = new FileTest('/work/Web/DEV/GDO/test/file/bidule/chouette', array(
	'chouette',
	'E:/work/Web/DEV/GDO/test/file/bidule/chouette',
	'E:/work/Web/DEV/GDO/test/file/bidule'
));
$test->start();

$test = new FileTest('/work/Web/DEV/Evolya3/SRC/test-gdo/bidule/chouette', array(
	'chouette',
	'E:/work/Web/DEV/Evolya3/SRC/test-gdo/bidule',
	'E:/work/Web/DEV/Evolya3/SRC/test-gdo'
));
$test->start();

echo '<hr />';

$test = new FileTest2('/work/Web/DEV/Evolya3/SRC/gdo/', array(
	TRUE,
	FALSE,
	TRUE
));
$test->start();

$test = new FileTest2('/work/Web/DEV/Evolya3/SRC/gdo/test-gdo', array(
	TRUE,
	FALSE,
	TRUE
));
$test->start();

$test = new FileTest2('/work/Web/DEV/Evolya3/SRC/gdo/bidule', array(
	FALSE,
	FALSE,
	FALSE
));
$test->start();

$test = new FileTest2('/work/Web/DEV/Evolya3/SRC/gdo/bidule/chouette/', array(
	FALSE,
	FALSE,
	FALSE
));
$test->start();

$test = new FileTest2('index.php', array(
	TRUE,
	TRUE,
	FALSE
));
$test->start();

$test = new FileTest2('../index.php', array(
	TRUE,
	TRUE,
	FALSE
));
$test->start();

$test = new FileTest2('E:/work/Web/DEV/GDO/test/', array(
	TRUE,
	FALSE,
	TRUE
));
$test->start();

$test = new FileTest2('E:/work/Web/DEV/GDO/test/index.php', array(
	TRUE,
	TRUE,
	FALSE
));
$test->start();

$test = new FileTest2('E:/work/Web/DEV/GDO/test/bidule/index.php', array(
	FALSE,
	FALSE,
	FALSE
));
$test->start();

echo '<hr />';

$test = new FileTest3('.', NULL, array(
	'file',
	'E:/work/Web/DEV/GDO/test/file',
	'E:/work/Web/DEV/Evolya3/SRC/gdo/test-gdo'
));
$test->start();

$test = new FileTest3('.', 'newdir', array(
	'newdir',
	'E:/work/Web/DEV/GDO/test/file/newdir',
	'E:/work/Web/DEV/GDO/test/file'
));
$test->start();

$test = new FileTest3('.', '///newdir', array(
	'newdir',
	'E:/work/Web/DEV/GDO/test/file/newdir',
	'E:/work/Web/DEV/GDO/test/file'
));
$test->start();

echo '<hr />';

$test = new FileTest4();
$test->start();

$test = new FileTest5();
$test->start();

$test = new FileTest6();
$test->start();

echo '<hr />';

$test = new FileTest7('..');
$test->start();

$test = new FileTest7('bla bla');
$test->start();

$test = new FileTest7('index.php');
$test->start();

?>
</div>
</body>
</html>