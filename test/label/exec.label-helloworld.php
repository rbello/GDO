<?php
require_once '../../src/evolya.gdo.php';
$label = new DLabel('Hello World');
if (!defined('DO_NOT_RENDER')) $label->render();
?>