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
    <title>回答 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <form method="post" action="result.php">
      <h2>回答</h2>
        <table class="table table-striped text-center">
          <thead>
            <tr>
              <th class="text-center">設問</th>
              <th class="text-center"><?= $choice[0] ?></th>
              <th class="text-center"><?= $choice[1] ?></th>
              <th class="text-center"><?= $choice[2] ?></th>
              <th class="text-center"><?= $choice[3] ?></th>
              <th class="text-center"><?= $choice[4] ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= h($_POST['q1']) ?></td>
              <td><input type="radio" name="a1" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a1" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a1" value="<?= $choice[2] ?>" checked></td>
              <td><input type="radio" name="a1" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a1" value="<?= $choice[4] ?>"></td>
            </tr>
            <tr>
              <td><?= h($_POST['q2']) ?></td>
              <td><input type="radio" name="a2" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a2" value="<?= $choice[1] ?>" checked></td>
              <td><input type="radio" name="a2" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a2" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a2" value="<?= $choice[4] ?>"></td>
            </tr>
            <tr>
              <td><?= h($_POST['q3']) ?></td>
              <td><input type="radio" name="a3" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[4] ?>" checked></td>
            </tr>
            <tr>
              <td><?= h($_POST['q4']) ?></td>
              <td><input type="radio" name="a4" value="<?= $choice[0] ?>" checked></td>
              <td><input type="radio" name="a4" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a4" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a4" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a4" value="<?= $choice[4] ?>"></td>
            </tr>
            <tr>
              <td><?= h($_POST['q5']) ?></td>
              <td><input type="radio" name="a5" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a5" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a5" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a5" value="<?= $choice[3] ?>" checked></td>
              <td><input type="radio" name="a5" value="<?= $choice[4] ?>"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" name="q1" value="<?= $_POST['q1'] ?>">
        <input type="hidden" name="q2" value="<?= $_POST['q2'] ?>">
        <input type="hidden" name="q3" value="<?= $_POST['q3'] ?>">
        <input type="hidden" name="q4" value="<?= $_POST['q4'] ?>">
        <input type="hidden" name="q5" value="<?= $_POST['q5'] ?>">
        <input class="btn btn-primary btn-block" type="submit" value="Submit">
      </form>
      <?php
      echo "<h2>受信結果</h2>";
      echo "<h3>設問内容</h3>";
      echo "<p>".
           "設問1：".$_POST['q1']."<br>".
           "設問2：".$_POST['q2']."<br>".
           "設問3：".$_POST['q3']."<br>".
           "設問4：".$_POST['q4']."<br>".
           "設問5：".$_POST['q5']."<br>".
           "</p>";
      echo "<h2>送信結果</h2>";
      echo "<h3>回答内容</h3>";
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