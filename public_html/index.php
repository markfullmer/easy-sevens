<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scrabble bingo trainer by Mark Fullmer</title>
  <meta name="apple-mobile-web-app-title" content="Mark Fullmer">
  <meta name="description" content="Work on pattern recognition of seven-letter words to improve your Scrabble game.">
  <meta property="og:title" content="Scrabble bingo trainer by Mark Fullmer">
  <meta property="og:description" content="Work on pattern recognition of seven-letter words to improve your Scrabble game." />
  <meta property="og:url" content="https://scrabble.markfullmer.com/" />
  <meta property="og:site_name" content="Scrabble bingo trainer by Mark Fullmer" />
  <meta property="og:type" content="website">
  <link rel="canonical" href="https://scrabble.markfullmer.com/" />
    <style>
      <?php require 'style.css'; ?>
    </style>
</head>
<body>
<?php

require '../vendor/autoload.php';

use GuzzleHttp\Client;
$client = new Client([
  'base_uri' => 'https://api.dictionaryapi.dev/api/v2/entries/en/',
  'timeout'  => 2.0,
]);

$list = 'aert';
if (isset($_GET['list'])) {
  if (in_array($_GET['list'], ['q-no-u', 'ersn', 'erst', 'ers', 'ings', 'aert', 'eing', 'two', 'three'])) {
    $list = $_GET['list'];
  }
}
$return = $_SERVER['SERVER_NAME'] . '?list=' . $list;
$words = file_get_contents('../' . $list . '.csv');
$list = explode(PHP_EOL, $words);
$clean = [];
foreach ($list as $item) {
  $clean[] = strtoupper(trim($item));
}
$complete_list = $_REQUEST['complete'] ?? '';
$complete_items = explode(',', $complete_list);
$streak = 0;
if (isset($_REQUEST['guess']) && isset($_REQUEST['actual'])) {
  $actual = base64_decode($_REQUEST['actual']);
  $guess = trim(strtoupper($_REQUEST['guess']));
  try {
    $response = $client->request('GET', $actual);
    $code = $response->getStatusCode();
    if ($code === 200) {
      $body = $response->getBody();
      $dict = json_decode($body, TRUE);
      if (isset($dict[0]['meanings'][0]['definitions'][0]['definition'])) {
        $definition = $dict[0]['meanings'][0]['definitions'][0]['definition'];
        $definition = '<div class="definition"><strong>' . $actual . '</strong>: ' . $definition . '</div>';
      }
    }
  }
  catch  (Exception $e) {
    // Probably a 404.
  }
  if (isset($definition)) {
    echo $definition;
  }
  if (in_array($guess, $clean)) {
    echo 'Got it! ';
    $streak = $_REQUEST['streak'] ?: 0;
    $streak = (int) $streak;
    $streak++;
    $next = $streak + 1;
    $complete_items[] = $_REQUEST['id'];
    echo 'Current streak: <span class="firework">' . $streak . '</span>';
    echo "<br />Try for $next? ";
  }
  else {
    echo "Nope! The word was <a href='https://www.thefreedictionary.com/" . $actual . "'>" . $actual . "</a>";
    echo "Try again?";
  }
}
else {
  echo "<p>Guess this scrambled word:</p>";
}
foreach ($complete_items as $key) {
  unset($list[$key]);
}
if (empty($list)) {
  echo 'You completed the entire list! Reload the page to start over';
  die();
}
$random = array_rand($list);
$word = trim($list[$random]);
$stringParts = str_split($word);
sort($stringParts);
$shuffled = implode($stringParts);
$length = strlen($shuffled);
echo "<p>" . strtoupper($shuffled) . "</p>";
echo '<form action="//' . $return . '" method="POST">';
?>

      <input type="text" pattern="[<?php echo $shuffled; echo strtoupper($shuffled); ?>]{<?php echo $length; ?>}" autofocus title="Must be seven letters, matching <?php echo strtoupper($shuffled); ?>" required name="guess"></input>
      <input type="hidden" name="actual" value="<?php echo base64_encode($word); ?>" />
      <input type="hidden" name="streak" value="<?php echo $streak; ?>" />
      <input type="hidden" name="id" value="<?php echo $random; ?>" />
      <input type="hidden" name="complete" value="<?php echo implode(',', $complete_items); ?>" />
    </form>
  </body>
  <?php echo count($list); ?>
  <hr>
  <a href="/?list=q-no-u">Q without U words (55)</a> |
  <a href="/?list=ersn">'ERSN' words (693)</a> |
  <a href="/?list=aert">'AERT' words (545)</a> |
  <a href="/?list=eing">'EING' words (448)</a> |
  <a href="/?list=two">Twos (71)</a> |
  <a href="/?list=three">Threes (591)</a>
</html>
