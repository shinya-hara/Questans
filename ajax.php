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

// セッションの開始
session_start();

// テキストエリアが空白の場合、make.phpから作成できないようにすれば以下は不要
// セッション変数を全て解除
// $_SESSION = array();

// アンケートタイトルをセッション変数に格納
if (isset($_POST['title'])) {
  $_SESSION['title'] = trim($_POST['title'], '　, ');
}

// 質問数をセッション変数に格納
if (isset($_POST['num'])) {
  $_SESSION['num'] = $_POST['num'];
}

// 質問をセッション変数に格納
for ($i=1; $i<=$_SESSION['num']; $i++) {
  if (isset($_POST['q'.$i])) {
    $_SESSION['q'.$i] = trim($_POST['q'.$i], '　, ');
  }
}

require_once __DIR__.'/db_info.php';
try {
  $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  try {
    // タイトルの取得
    $stmt = $dbh->prepare("select title from questionnaries where title = ? limit 1");
    $stmt->bindValue(1, $_SESSION['title']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $rowCount = count($result);   // 入力されたタイトルと同じタイトルのレコードがあるかチェック
    if ($rowCount > 0) {
      $_SESSION['status'] = "danger";
      $_SESSION['flash_msg'] = "そのタイトルはすでに存在します．タイトルを変更してください．";
      $_SESSION['flash_flag'] = true;
    }
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "タイトルの取得に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
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
      <h1>アンケートシステム</h1>
      <a href="management.php"><input type="button" value="管理画面" class="btn btn-default"></a>
      <hr>
      <h2>確認</h2>
      <div id="flash"><?php include 'flash.php'; ?></div>
      <p>ユーザからは以下のように表示されます．よろしいですか？</p>
      <a href="make.php"><button type="button" id="edit" class="btn btn-default">修正</button></a>
      <button type="button" id="insert" class="btn btn-primary">OK</button>
      <hr>
      <h3><?= $_SESSION['title'] ?></h3>
      <table class="table table-striped text-center">
        <thead>
          <tr>
            <th>番号</th>
            <th>質問</th>
            <?php for($i = 0; $i < $c_size; $i++): ?>
            <th class="text-center"><?= $choice[$i] ?></th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
          <tr>
            <td><?= $i ?></td>
            <td class="text-left"><?= h($_SESSION['q'.$i]) ?></td>
            <?php for($j = 0; $j < $c_size; $j++): ?>
            <td><input type="radio" name="a<?= $i ?>" value="<?= $choice[$j] ?>"></td>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      <input type="button" class="btn btn-primary btn-block" value="回答">
      <div id="load"></div>
      <?php include 'debug.php'; ?>
    </div><!-- /container -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
    $(function(){
      // タイトルが重複していた場合、ボタンを無効化
      if (<?=$rowCount?> > 0) {
        console.log("<?=$rowCount?>");
        $('#insert').prop('disabled', true);
      }
      
      // OKボタンを押した時の処理
      $('#insert').on('click', function() {
        // アンケート作成に成功した場合、insert.phpはエコーバックしない
        // 失敗した場合、エラーメッセージを出力し、ボタンを無効化
        $('#flash').load('insert.php', function(data) {
          if (data === "") {  // 作成成功時はリダイレクト
            window.location.href = "management.php";
          } else {
            $('#insert').prop('disabled', true);
          }
        });
      });
      
      // ページから離れる際に確認
      var isChanged = false;  // フォームの状態を表すフラグ
      $(window).on('beforeunload', function() {
        console.log(isChanged);
        if (isChanged) {
          return "このページを離れようとしています．";
        }
      });
      // フォームに変更があった際に空欄でなければフラグを立てる
      $('form input, form textarea').on('change', function() {
        if ($('form input').val() !== "" || $('form textarea').val() !== "") {
          isChanged = true;
        } else {  // フォームが空欄になったらフラグを落とす
          isChanged = false;
        }
      });
      // このページに遷移後、フォームが空欄でなければフラグを立てる
      if ($('form input').val() !== "" || $('form textarea').val() !== "") {
        isChanged = true;
      }
      $('button#insert, button#edit').on('click', function() {
        // ボタンを押した際にフラグを落とす
        isChanged = false;
      });
    });
    </script>
  </body>
</html>