<?php
// エスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 選択肢の設定
$choice = [ '全くそう思わない',
            'あまりそう思わない',
            'どちらとも言えない',
            'ややそう思う',
            '非常にそう思う' ];

// 選択肢のサイズ
$size = count($choice);

// セッションの開始
session_start();

// 質問数をセッション変数に格納
if ($_POST['num']) {
  $_SESSION['num'] = $_POST['num'];
}

// 質問をセッション変数に格納
for ($i=1; $i<=$_SESSION['num']; $i++) {
  if ($_POST['q'.$i]) {
    $_SESSION['q'.$i] = $_POST['q'.$i];
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>確認 | アンケートシステム</title>
    <style>
      .table tbody>tr>td {
        vertical-align: middle;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>確認</h2>
      <h3>質問数：<?= $_SESSION['num'] ?></h3>
      <p>ユーザからは以下のように表示されます．よろしいですか？</p>
      <form method="post" action="output.php">
        <a href="make.php"><input type="button" value="修正" class="btn btn-default"></a>
        <input type="submit" value="OK" class="btn btn-primary">
      </form>
      <hr>
      <table class="table table-striped text-center">
        <thead>
          <tr>
            <th>質問</th>
            <?php for($i = 0; $i < $size; $i++): ?>
            <th class="text-center"><?= $choice[$i] ?></th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
          <tr>
            <td class="text-left"><?= h($_SESSION['q'.$i]) ?></td>
            <?php for($j = 0; $j < $size; $j++): ?>
            <td><input type="radio" name="a<?= $i ?>" value="<?= $choice[$j] ?>"></td>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      <input type="button" class="btn btn-primary btn-block" value="回答">
    </div><!-- /container -->
  </body>
</html>