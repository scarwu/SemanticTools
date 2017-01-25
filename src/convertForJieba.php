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

@mkdir("{$root}/output");

$outputPath = "{$root}/output/moedict-jieba.txt";
$handle = fopen($outputPath, 'w+');

$inputPath = "{$root}/clone/moedict-data/dict-cat.json";
$inputData = json_decode(file_get_contents($inputPath), true);

foreach ($inputData as $data) {
    foreach ($data['entries'] as $text) {
        fwrite($handle, "{$text} 50 n\n");
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
