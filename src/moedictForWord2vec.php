#!/usr/bin/env php
<?php

function utf8($num)
{
    if ($num <= 0x7F) {
        return chr($num);
    }

    if ($num <= 0x7FF) {
        return chr(($num >> 6) + 192)
            . chr(($num & 63) + 128);
    }

    if ($num <= 0xFFFF) {
        return chr(($num >> 12) + 224)
            . chr((($num >> 6) & 63) + 128)
            . chr(($num & 63) + 128);
    }

    if ($num <= 0x1FFFFF) {
        return chr(($num >> 18) + 240)
            . chr((($num >> 12) & 63) + 128)
            . chr((($num >> 6) & 63) + 128)
            . chr(($num & 63) + 128);
    }

    return '';
}

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

$outputPath = "{$root}/output/word2vec-moedict.txt";
$handle = fopen($outputPath, 'w+');

echo "Load input\n";

$inputPath = "{$root}/clone/moedict-data/dict-revised.json";
$inputData = json_decode(file_get_contents($inputPath), true);

$total = count($inputData);

foreach ($inputData as $index => $data) {
    $index = $index + 1;

    echo "{$index} / {$total}\r";

    $text = [];

    if (preg_match('/\{\[(.+)\]\}/', $data['title'], $match)) {
        $data['title'] = utf8(hexdec($match[1]));
    }

    if (preg_match('/(.+)（.+）/', $data['title'], $match)) {
        $data['title'] = $match[1];
    }

    $text[] = $data['title'];

    if (isset($data['heteronyms'])) {
        $heteronyms = $data['heteronyms'];

        foreach ($heteronyms as $heteronym) {
            if (!isset($heteronym['definitions'])) {
                continue;
            }

            $definitions = $heteronym['definitions'];

            foreach ($definitions as $definition) {
                if (isset($definition['def'])) {
                    $text[] = $definition['def'];
                }

                if (isset($definition['quote'])) {
                    $text[] = implode(' ', $definition['quote']);
                }
            }
        }
    }

    $text = implode(' ', $text);
    $text = Jieba::cut($text, true);
    $text = implode(' ', $text);

    fwrite($handle, "{$text} \n");
}

echo "\n";

fclose($handle);
