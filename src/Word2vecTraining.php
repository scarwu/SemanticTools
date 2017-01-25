#!/usr/bin/env php
<?php

$root = realpath(dirname(__FILE__) . '/..');

$word2vec = "{$root}/clone/word2vec/trunk/word2vec";
$train = "{$root}/output/word2vec-moedict.txt";
$output = "{$root}/output/vectors.bin";

$command = "{$word2vec} -train {$train} -output {$output} "
    . '-cbow 0 -size 200 -window 10 -negative 5 -hs 0 '
    . '-sample 1e-4 -threads 24 -binary 1 -iter 20 -min-count 1';

system($command);
