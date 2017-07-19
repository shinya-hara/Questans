<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_unlogined_session();

// ユーザから受け取ったユーザ名
$username = trim_emspace(filter_input(INPUT_POST, 'username'));
$userpass = filter_input(INPUT_POST, 'password');
// POSTメソッドのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (validate_token(filter_input(INPUT_POST, 'token'))) {
    try {
      $dbh = new PDO($dsn, $user, $password,
                     [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_EMULATE_PREPARES => false ]);
      try {
        $stmt = $dbh->prepare("select user_name from users where user_name = ?");
        $stmt->bindValue(1, $username);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $rowCount = count($result);   // 同じユーザ名のレコード件数 0 or 1
        
        if ($rowCount > 0) {          // 同じユーザ名が登録済み
          $_SESSION['status'] = "danger";
          $_SESSION['flash_msg'] = "ユーザ名 ".$username." は既に登録されています．";
          $_SESSION['flash_flag'] = true;
        } else {
          $dbh->beginTransaction();     // トランザクションの開始
          $stmt = $dbh->prepare("insert into users (user_name, password, role) values (?, ?, 2)");
          $stmt->bindValue(1, $username);
          $stmt->bindValue(2, password_hash($userpass, PASSWORD_BCRYPT));
          $stmt->execute();
          $_SESSION['status'] = "success";
          $_SESSION['flash_msg'] = "ユーザ名 ".$username." を登録しました．ログインしてください.";
          $_SESSION['flash_flag'] = true;
          $dbh->commit();
          // ログイン完了後に /login.php に遷移
          header('Location: /login.php');
          exit;
        }
      } catch (PDOException $e) {
        $dbh->rollBack();
        $_SESSION['status'] = "danger";
        $_SESSION['flash_msg'] = "ユーザ名 ".$username." の登録に失敗しました．";
        $_SESSION['flash_flag'] = true;
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
      }
    } catch (PDOException $e) {
      header('Content-Type: text/plain; charset=UTF-8', true, 500);
      exit('データベースの接続に失敗しました．');
    }
  }
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>ユーザ登録 | アンケートシステム</title>
  </head>
  <body>
    <header>
      <div class="container clearfix">
        <h1 class="pull-left"><a href="/management.php"><span style="color:#DF0101;">Q</span>uest<span style="color:#DF0101;">a</span>ns</a></h1>
        <div class="buttons">
          <button class="btn btn-primary pull-right" type="button">ユーザ登録</button>
          <a href="login.php"><button class="btn btn-info pull-right" type="button">ログイン</button></a>
        </div>
      </div>
    </header>
    <div class="container">
      <div class="alert alert-info">
        ユーザ名は半角英数3文字以上<br>
        パスワードは半角英数4文字以上
      </div>
      <?php include __DIR__.'/flash.php'; ?>
      <div class="login-container">
        <div class="avatar"></div>
        <div class="form-box">
          <form action="" method="post">
            <input pattern="^[0-9A-Za-z]+$" minlength="3" maxlength="20" name="username" type="text" placeholder="ユーザ名" class="username">
            <input pattern="^[0-9A-Za-z]+$" minlength="4" name="password" type="password" placeholder="パスワード" class="pw1">
            <input pattern="^[0-9A-Za-z]+$" minlength="4" name="confirm" type="password" placeholder="パスワード（確認）" class="pw2">
            <input type="hidden" name="token" value="<?=h(generate_token())?>">
            <button class="btn btn-primary btn-block mtop" type="submit" id="signup" disabled>ユーザ登録</button>
            <div class="text-center">or</div>
            <button class="btn btn-info btn-block" type="button" onClick="location.href='login.php'">ログイン</button>
          </form>
        </div>
      </div>
    </div>
    <?php include __DIR__.'/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
    $(function() {
      $('input[name="username"]').focus();
      
      var name_flg   = false;
      var pw1_flg    = false;
      var pw2_flg    = false;
      var match_flg  = false;
      
      // フラグの状態に応じてボタンの状態を決定する
      function setBtnState(name_flg, pw1_flg, pw2_flg, match_flg) {
        // フラグにfalseがある場合（不正入力がある場合）
        if (!name_flg || !pw1_flg || !pw2_flg || !match_flg) {
          $('#signup').prop('disabled', true);
        } else {  // 正しく入力されている場合
          $('#signup').prop('disabled', false);
        }
      }
      
      $('.username').on('keyup', function() {
        name_flg = $('.username').val().length >= 3
          ? true
          : false
        setBtnState(name_flg, pw1_flg, pw2_flg, match_flg);
      });
      
      $('.pw1').on('keyup', function() {
        pw1_flg = $('.pw1').val().length >= 4
          ? true
          : false
        match_flg = $('.pw1').val() === $('.pw2').val()
          ? true
          : false
        setBtnState(name_flg, pw1_flg, pw2_flg, match_flg);
      });
      
      $('.pw2').on('keyup', function() {
        pw2_flg = $('.pw2').val().length >= 4
          ? true
          : false
        match_flg = $('.pw1').val() === $('.pw2').val()
          ? true
          : false
        setBtnState(name_flg, pw1_flg, pw2_flg, match_flg);
      });
    });
    </script>
  </body>
</html>