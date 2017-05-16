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
$c_size = count($choice);

session_start();

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
              <th>質問</th>
              <?php for($i = 0; $i < $c_size; $i++): ?>
              <th class="text-center"><?= $choice[$i] ?></th>
              <?php endfor; ?>
            </tr>
          </thead>
          <tbody>
            <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
            <tr>
              <td class="text-left"><?= h($_SESSION['q'.$i]) ?></td>
              <?php for($j = 0; $j < $c_size; $j++): ?>
              <td><input type="radio" name="a<?= $i ?>" value="<?= $choice[$j] ?>"></td>
              <?php endfor; ?>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
        <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
        <input type="hidden" name="q<?= $i ?>" value="<?= h($_SESSION['q'.$i]) ?>" >
        <?php endfor; ?>
        <input type="hidden" name="num" value="<?= $_SESSION['num'] ?>" >
        <input class="btn btn-primary btn-block" type="submit" value="回答">
      </form>
    </div><!-- container -->
  </body>
</html>
 
<!-- ここがバッファされます -->
<?php
// 同階層の questionnarie.php にphp実行結果を出力
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
      <a href="make.php"><input type="button" value="アンケート作成画面へ" class="btn btn-primary"></a>
      <hr>
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="text-center">番号</th><th>質問</th>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
          <tr>
            <td class="text-center"><?= $i ?></td>
            <td><?= h($_SESSION['q'.$i]) ?></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      <?php include 'debug.php'; ?>
    </div>
  </body>
</html>