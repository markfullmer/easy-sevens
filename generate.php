<?php

$file = file_get_contents('dictionary.csv');
$words = explode(PHP_EOL, $file);

$list = [];
foreach ($words as $word) {
  if (strlen($word) === 4) {
    $list[] = $word;
  }
  // if (strpos($word, 'e') !== FALSE && strpos($word, 'r') !== FALSE && strpos($word, 't') !== FALSE && strpos($word, 's') === FALSE && strpos($word, 'a') !== FALSE) {
  //   $list[] = $word;
  // }
}
print_r($list);
print_r(count($list));
file_put_contents('three.csv', implode(PHP_EOL, $list));
