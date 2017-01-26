#!/usr/bin/env php
<?php

$root = realpath(dirname(__FILE__) . '/..');

$outputPath = "{$root}/output/word2vec-all.txt";
$outputHandle = fopen($outputPath, 'w+');

$inputPathList = [
    "{$root}/output/word2vec-moedict.txt",
    "{$root}/output/word2vec-redmansions.txt"
];

foreach ($inputPathList as $inputPath) {
    if (!file_exists($inputPath)) {
        continue;
    }

    fwrite($outputHandle, file_get_contents($inputPath));
}

fclose($outputHandle);

$word2vec = "{$root}/clone/word2vec/trunk/word2vec";
$train = "{$root}/output/word2vec-all.txt";
$output = "{$root}/output/vectors.bin";

$command = "{$word2vec} -train {$train} -output {$output} "
    . '-cbow 0 -size 200 -window 10 -negative 5 -hs 0 '
    . '-sample 1e-4 -threads 24 -binary 1 -iter 20 '
    . '-min-count 1 < `tty` > `tty`';

system($command);
