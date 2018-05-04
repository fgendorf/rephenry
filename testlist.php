<?php

require 'vendor/autoload.php';

$test = new REPHenry();
$test->setServer("10.0.0.86");
$test->setPort("3000");
$test->connect();
echo $test->queryREP("00+RR+00+N]100]1").PHP_EOL;