#!/usr/bin/env php
<?php

function utf8($num)
{
    if($num<=0x7F)       return chr($num);
    if($num<=0x7FF)      return chr(($num>>6)+192).chr(($num&63)+128);
    if($num<=0xFFFF)     return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
    if($num<=0x1FFFFF)   return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128).chr(($num&63)+128);
    return '';
}

$root = realpath(dirname(__FILE__) . '/..');

include "{$root}/vendor/autoload.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

// Initialize Jieba
Jieba::init([
    'mode' => 'default',
    'dict' => 'big' // using built-in triditional chinese dict
]);
Finalseg::init();

// using 萌典 as extends dictionary
// src: https://github.com/g0v/moedict-data
Jieba::loadUserDict("{$root}/output/jieba-moedict.txt");

@mkdir("{$root}/output");

$outputPath = "{$root}/output/word2vec-moedict.txt";
$handle = fopen($outputPath, 'w+');

$inputPath = "{$root}/clone/moedict-data/dict-cat.json";
$json = file_get_contents($inputPath);
$json = json_decode($json, true);

foreach ($inputData as $data) {
    foreach ($data['entries'] as $text) {
        $line = Jieba::cut($text, true);
        $line = implode(' ', $line);

        fwrite($handle, $line);
    }
}

$inputPath = "{$root}/clone/moedict-data/dict-revised.json";
$inputData = json_decode(file_get_contents($inputPath), true);

foreach ($inputData as $data) {
    $text = $data['title'];

    if (preg_match('/\{\[(.+)\]\}/', $text, $match)) {
        $text = utf8(hexdec($match[1]));
    }

    if (preg_match('/(.+)（.+）/', $text, $match)) {
        $text = $match[1];
    }

    fwrite($handle, "{$text} 50 n\n");
}

fclose($handle);
