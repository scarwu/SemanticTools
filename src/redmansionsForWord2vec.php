#!/usr/bin/env php
<?php

$root = realpath(dirname(__FILE__) . '/..');

include "{$root}/vendor/autoload.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

echo "Init Jieba\n";

// Initialize Jieba
Jieba::init([
    'mode' => 'default',
    'dict' => 'big' // using built-in triditional-chinese dict
]);
Finalseg::init();

// using 萌典 as extends dictionary
// src: https://github.com/g0v/moedict-data
Jieba::loadUserDict("{$root}/output/jieba-moedict.txt");

echo "Create output\n";

@mkdir("{$root}/output");

$outputPath = "{$root}/output/word2vec-redmansions.txt";
$outputHandle = fopen($outputPath, 'w+');

$inputPathList = [
    "{$root}/clone/Red_Mansions_Anasoft_A_CHT_Big5_txt.txt",
    "{$root}/clone/Red_Mansions_Anasoft_B_CHT_Big5_txt.txt"
];

foreach ($inputPathList as $inputPath) {
    $filename = explode('/', $inputPath);
    $filename = array_pop($filename);

    echo "Load input: {$filename}\n";

    $inputHandle = fopen($inputPath, 'r');

    while ($text = fgets($inputHandle, 2048)) {
        $text = trim($text);
        $text = mb_convert_encoding($text, 'UTF-8', 'big5');

        if (0 === strlen($text)) {
            continue;
        }

        $text = Jieba::cut($text, true);
        $text = implode(' ', $text);

        fwrite($outputHandle, "{$text} \n");
    }

    fclose($inputHandle);
}

fclose($outputHandle);
