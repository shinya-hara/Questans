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
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>確認 | アンケートシステム</title>
    <style  type="text/css">
      .table tbody>tr>td {
        vertical-align: middle;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>確認</h2>
      <p>ユーザからは以下のように表示されます．よろしいですか？</p>
      <form method="post" action="output.php">
        <input type="hidden" name="q1" value="<?= $_POST['q1'] ?>">
        <input type="hidden" name="q2" value="<?= $_POST['q2'] ?>">
        <input type="hidden" name="q3" value="<?= $_POST['q3'] ?>">
        <input type="hidden" name="q4" value="<?= $_POST['q4'] ?>">
        <input type="hidden" name="q5" value="<?= $_POST['q5'] ?>">
        <a href="make.php"><input type="button" value="修正" class="btn btn-default"></a>
        <input type="submit" value="OK" class="btn btn-primary">
      </form>
      <hr>
      <table class="table table-striped text-center">
        <thead>
          <tr>
            <th>設問</th>
            <th class="text-center"><?= $choice[0] ?></th>
            <th class="text-center"><?= $choice[1] ?></th>
            <th class="text-center"><?= $choice[2] ?></th>
            <th class="text-center"><?= $choice[3] ?></th>
            <th class="text-center"><?= $choice[4] ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?= h($_POST['q1']) ?></td>
            <td><input type="radio" name="a1" value="<?= $choice[0] ?>"></td>
            <td><input type="radio" name="a1" value="<?= $choice[1] ?>"></td>
            <td><input type="radio" name="a1" value="<?= $choice[2] ?>" checked></td>
            <td><input type="radio" name="a1" value="<?= $choice[3] ?>"></td>
            <td><input type="radio" name="a1" value="<?= $choice[4] ?>"></td>
          </tr>
          <tr>
            <td class="text-left"><?= h($_POST['q2']) ?></td>
            <td><input type="radio" name="a2" value="<?= $choice[0] ?>"></td>
            <td><input type="radio" name="a2" value="<?= $choice[1] ?>" checked></td>
            <td><input type="radio" name="a2" value="<?= $choice[2] ?>"></td>
            <td><input type="radio" name="a2" value="<?= $choice[3] ?>"></td>
            <td><input type="radio" name="a2" value="<?= $choice[4] ?>"></td>
          </tr>
          <tr>
            <td class="text-left"><?= h($_POST['q3']) ?></td>
            <td><input type="radio" name="a3" value="<?= $choice[0] ?>"></td>
            <td><input type="radio" name="a3" value="<?= $choice[1] ?>"></td>
            <td><input type="radio" name="a3" value="<?= $choice[2] ?>"></td>
            <td><input type="radio" name="a3" value="<?= $choice[3] ?>"></td>
            <td><input type="radio" name="a3" value="<?= $choice[4] ?>" checked></td>
          </tr>
          <tr>
            <td class="text-left"><?= h($_POST['q4']) ?></td>
            <td><input type="radio" name="a4" value="<?= $choice[0] ?>" checked></td>
            <td><input type="radio" name="a4" value="<?= $choice[1] ?>"></td>
            <td><input type="radio" name="a4" value="<?= $choice[2] ?>"></td>
            <td><input type="radio" name="a4" value="<?= $choice[3] ?>"></td>
            <td><input type="radio" name="a4" value="<?= $choice[4] ?>"></td>
          </tr>
          <tr>
            <td class="text-left"><?= h($_POST['q5']) ?></td>
            <td><input type="radio" name="a5" value="<?= $choice[0] ?>"></td>
            <td><input type="radio" name="a5" value="<?= $choice[1] ?>"></td>
            <td><input type="radio" name="a5" value="<?= $choice[2] ?>"></td>
            <td><input type="radio" name="a5" value="<?= $choice[3] ?>" checked></td>
            <td><input type="radio" name="a5" value="<?= $choice[4] ?>"></td>
          </tr>
        </tbody>
      </table>
      <input type="button" class="btn btn-primary btn-block" value="回答">
    </div><!-- container -->
  </body>
</html>