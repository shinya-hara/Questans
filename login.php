<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_unlogined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("select name,password from users");
    $stmt->execute();
    // キーがユーザ名、値がパスワードの連想配列を作る
    while ($row = $stmt -> fetch()) {
      $hashes[$row['name']] = $row['password'];
    }
  } catch (PDOException $e) {
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit('ユーザ情報の取得に失敗しました．');
  }
  
} catch (PDOException $e) {
  header('Content-Type: text/plain; charset=UTF-8', true, 500);
  exit('データベースの接続に失敗しました．');
}

// ユーザから受け取ったユーザ名とパスワード
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
// POSTメソッドのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (
    validate_token(filter_input(INPUT_POST, 'token')) &&
    password_verify(
      $password,
      isset($hashes[$username])
      ? $hashes[$username]
      : '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
      )
    ) {
    // 認証が成功したとき
    // セッションIDの追跡を防ぐ
    session_regenerate_id(true);
    // ログイン完了後にフラッシュメッセージを表示する
    $_SESSION['status'] = "success";
    $_SESSION['flash_msg'] = "ようこそ，".$username."さん";
    $_SESSION['flash_flag'] = true;
    // ユーザ名をセット
    $_SESSION['username'] = $username;
    // ログイン完了後に /management.php に遷移
    header('Location: /management.php');
    exit;
  }
  // 認証が失敗したとき
  // 「403 Forbidden」
  http_response_code(403);
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "ユーザ名またはパスワードが違います";
  $_SESSION['flash_flag'] = true;
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
    <title>ログイン | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <!--<h2>ログイン</h2>-->
      <!--<form method="post" action="">-->
      <!--  <div class="form-group">-->
      <!--    <label for="username">Name:</label>-->
      <!--    <input type="text" class="form-control" id="username" name="username">-->
      <!--  </div>-->
      <!--  <div class="form-group">-->
      <!--    <label for="password">Password:</label>-->
      <!--    <input type="password" class="form-control" id="password" name="password">-->
      <!--  </div>-->
      <!--  <input type="hidden" name="token" value="<?=h(generate_token())?>">-->
      <!--  <button type="submit" class="btn btn-primary">Login</button>-->
      <!--</form>-->
      
      <?php include __DIR__.'/flash.php'; ?>
      <div class="login-container">
        <div class="avatar"></div>
        <div class="form-box">
          <form action="" method="post">
            <input name="username" type="text" placeholder="username">
            <input name="password" type="password" placeholder="password">
            <input type="hidden" name="token" value="<?=h(generate_token())?>">
            <button class="btn btn-info btn-block login" type="submit">Login</button>
            <div class="text-center">or</div>
            <div class="clearfix">
              <a href="#"><button class="btn btn-primary pull-left signup" type="button">Sign Up</button></a>
              <a href="#"><button class="btn btn-warning pull-right guest" type="button">Guest</button></a>
            </div>
          </form>
        </div>
      </div>
      
      <p class="text-center">ユーザ名：user パスワード：user でテストユーザとしてログイン可能</p>
      <?php if (http_response_code() === 403): ?>
        <!--<p style="color: red;">ユーザ名またはパスワードが違います</p>-->
      <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>