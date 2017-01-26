#!/usr/bin/env php
<?php

$root = realpath(dirname(__FILE__) . '/..');

$distance = "{$root}/clone/word2vec/trunk/distance";
$vectors = "{$root}/output/vectors.bin";

system("{$distance} {$vectors} < `tty` > `tty`");
