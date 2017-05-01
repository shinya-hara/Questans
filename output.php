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

// 設問を格納
$question = [ $_POST['q1'],
              $_POST['q2'],
              $_POST['q3'],
              $_POST['q4'],
              $_POST['q5'] ];

/**
 * phpの実行結果をhtmlファイルへ書き出す簡単なサンプル
 */
 
// 出力のバッファリングを有効にする
ob_start();
?>
<!-- ここから -->
 
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
              <td class="text-left"><?= h($question[0]) ?></td>
              <td><input type="radio" name="a1" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a1" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a1" value="<?= $choice[2] ?>" checked></td>
              <td><input type="radio" name="a1" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a1" value="<?= $choice[4] ?>"></td>
            </tr>
            <tr>
              <td class="text-left"><?= h($question[1]) ?></td>
              <td><input type="radio" name="a2" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a2" value="<?= $choice[1] ?>" checked></td>
              <td><input type="radio" name="a2" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a2" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a2" value="<?= $choice[4] ?>"></td>
            </tr>
            <tr>
              <td class="text-left"><?= h($question[2]) ?></td>
              <td><input type="radio" name="a3" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a3" value="<?= $choice[4] ?>" checked></td>
            </tr>
            <tr>
              <td class="text-left"><?= h($question[3]) ?></td>
              <td><input type="radio" name="a4" value="<?= $choice[0] ?>" checked></td>
              <td><input type="radio" name="a4" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a4" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a4" value="<?= $choice[3] ?>"></td>
              <td><input type="radio" name="a4" value="<?= $choice[4] ?>"></td>
            </tr>
            <tr>
              <td class="text-left"><?= h($question[4]) ?></td>
              <td><input type="radio" name="a5" value="<?= $choice[0] ?>"></td>
              <td><input type="radio" name="a5" value="<?= $choice[1] ?>"></td>
              <td><input type="radio" name="a5" value="<?= $choice[2] ?>"></td>
              <td><input type="radio" name="a5" value="<?= $choice[3] ?>" checked></td>
              <td><input type="radio" name="a5" value="<?= $choice[4] ?>"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" name="q1" value="<?= $question[0] ?>">
        <input type="hidden" name="q2" value="<?= $question[1] ?>">
        <input type="hidden" name="q3" value="<?= $question[2] ?>">
        <input type="hidden" name="q4" value="<?= $question[3] ?>">
        <input type="hidden" name="q5" value="<?= $question[4] ?>">
        <input class="btn btn-primary btn-block" type="submit" value="回答">
      </form>
    </div><!-- container -->
  </body>
</html>
 
<!-- ここがバッファされます -->
<?php
// 同階層の test.html にphp実行結果を出力
file_put_contents( 'questionnarie.php', ob_get_contents() );
 
// 出力用バッファをクリア(消去)し、出力のバッファリングをオフにする
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>作成完了 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>作成完了</h2>
      <div class="alert alert-success" role="alert">
        以下の内容でアンケートを作成しました．
        <a href="questionnarie.php" target="_blank"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> アンケート画面へ</a>
      </div>
      <hr>
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="text-center">番号</th><th>設問</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center">1</td><td><?= h($question[0]) ?></td>
          </tr>
          <tr>
            <td class="text-center">2</td><td><?= h($question[1]) ?></td>
          </tr>
          <tr>
            <td class="text-center">3</td><td><?= h($question[2]) ?></td>
          </tr>
          <tr>
            <td class="text-center">4</td><td><?= h($question[3]) ?></td>
          </tr>
          <tr>
            <td class="text-center">5</td><td><?= h($question[4]) ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>