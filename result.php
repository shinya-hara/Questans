<?php
// エスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <title>結果 | アンケートシステム</title>
    <style type="text/css">
		</style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>アンケート結果</h2>
      <?php
      echo "<p>".
           "回答1：".$_POST['a1']."<br>".
           "回答2：".$_POST['a2']."<br>".
           "回答3：".$_POST['a3']."<br>".
           "回答4：".$_POST['a4']."<br>".
           "回答5：".$_POST['a5']."<br>".
           "</p>";
      ?>
    </div><!-- container -->
  </body>
</html>