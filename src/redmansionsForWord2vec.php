#!/usr/bin/env php
<?php

$root = realpath(dirname(__FILE__) . '/..');

include "{$root}/vendor/autoload.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

// Initialize Jieba
Jieba::init([
    'mode' => 'default',
    'dict' => 'big' // using built-in triditional-chinese dict
]);
Finalseg::init();

// using 萌典 as extends dictionary
// src: https://github.com/g0v/moedict-data
Jieba::loadUserDict("{$root}/output/jieba-moedict.txt");

@mkdir("{$root}/output");

$outputPath = "{$root}/output/word2vec-redmansions.txt";
$outputHandle = fopen($outputPath, 'w+');

$inputPath = "{$root}/clone/Red_Mansions_Anasoft_A_CHT_Big5_txt.txt";
$inputHandle = fopen($inputPath, 'r');

while ($text = fgets($inputHandle, 2048)) {
    $text = trim($text);
    $text = mb_convert_encoding($text, 'UTF-8', 'big5');

    if (0 === mb_strlen($text)) {
        continue;
    }

    $text = Jieba::cut($text, true);
    $text = implode(' ', $text);

    fwrite($outputHandle, "{$text} \n");
}

fclose($inputHandle);

$inputPath = "{$root}/clone/Red_Mansions_Anasoft_B_CHT_Big5_txt.txt";
$inputHandle = fopen($inputPath, 'r');

while ($text = fgets($inputHandle, 2048)) {
    $text = trim($text);
    $text = mb_convert_encoding($text, 'UTF-8', 'big5');

    if (0 === mb_strlen($text)) {
        continue;
    }

    $text = Jieba::cut($text, true);
    $text = implode(' ', $text);

    fwrite($outputHandle, "{$text} \n");
}

fclose($inputHandle);

fclose($outputHandle);
