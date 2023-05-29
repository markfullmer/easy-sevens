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
      body {
        font-size: 1rem;
        font-family: monospace;
        margin: auto;
        width: 50%;
      }
      input[type="text"]
      {
        font-size: 3rem;
        font-weight: bold;
        font-family: monospace;
        text-transform: uppercase;
      }
      .firework-wrapper {
        position: relative;
        display: inline;
      }
      .firework {
        background-image: url("firework.gif?<?php echo rand(0,1000); ?>");
        width: 200px;
        height: 200px;
        position: absolute;
        display: inline;
        background-size: contain;
      }
    </style>
</head>
<body>
<a href="/?list=q-no-u">Q without U words (75)</a> |
<a href="/?list=ersn">'ERSN' words (693)</a> |
<a href="/?list=erst">'ERST' words (1175)</a> |
<a href="/?list=ings">'INGS' words (626)</a>
<hr>

<?php

$list = 'ersn';
if (isset($_GET['list'])) {
  if (in_array($_GET['list'], ['q-no-u', 'ersn', 'erst', 'ers'])) {
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

$streak = 0;
if (isset($_REQUEST['guess']) && isset($_REQUEST['actual'])) {
  $guess = trim(strtoupper($_REQUEST['guess']));
  if (in_array($guess, $clean)) {
    echo '<h2>Got it!</h2>';
    $streak = $_REQUEST['streak'] ?: 0;
    $streak = (int) $streak;
    $streak++;
    $next = $streak + 1;
    echo 'Current streak: <div class="firework">' . $streak . '</div>';
    echo "<h2>Try for $next?</h2>";
  }
  else {
    $actual = base64_decode($_REQUEST['actual']);
    echo "<h2>Nope! The word was <a href='https://dictionary.com/browse/" . $actual . "'>" . $actual . "</a></h2>";
    echo "<h2>Try again?</h2>";
  }
}
else {
  echo "<h2>Guess this scrambled word:</h2>";
}
$total = count($list);
$random = rand(0, $total);
$word = trim($list[$random]);
$stringParts = str_split($word);
sort($stringParts);
$shuffled = implode($stringParts);
$length = strlen($shuffled);
echo "<h2>" . strtoupper($shuffled) . "</h2>";
echo '<form action="//' . $return . '" method="POST">';
?>

<input type="text" pattern="[<?php echo $shuffled; echo strtoupper($shuffled); ?>]{<?php echo $length; ?>}" autofocus title="Must be seven letters, matching <?php echo strtoupper($shuffled); ?>" required name="guess"></input>
<input type="hidden" name="actual" value="<?php echo base64_encode($word); ?>" />
<input type="hidden" name="streak" value="<?php echo $streak; ?>" />
</form>
</body>
</html>
