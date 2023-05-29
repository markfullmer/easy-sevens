<?php

$file = file_get_contents('sevens.csv');
$words = explode(PHP_EOL, $file);
$list = [];
foreach ($words as $word) {
  if (strpos($word, 'i') !== FALSE && strpos($word, 'n') !== FALSE && strpos($word, 's') !== FALSE && strpos($word, 'g') !== FALSE) {
    $list[] = $word;
  }
}
print_r($list);
print_r(count($list));
file_put_contents('ings.csv', implode(PHP_EOL, $list));
