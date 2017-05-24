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
    <title>ユーザ登録 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      まだユーザ登録できませーん
      <a href="login.php"><button class="btn btn-default" type="button">ログイン画面へ</button></a>
      <?php include __DIR__.'/flash.php'; ?>
      <div class="login-container">
        <div class="avatar"></div>
        <div class="form-box">
          <form action="" method="post">
            <input name="username" type="text" placeholder="username" class="username">
            <input name="password" type="password" placeholder="password" class="pw1">
            <input name="confirm" type="password" placeholder="confirm password" class="pw2">
            <button class="btn btn-primary btn-block mtop" type="submit" disabled>Sign Up</button>
            <div class="text-center">or</div>
            <button class="btn btn-info btn-block" type="button" onClick="location.href='login.php'">Login</button>
          </form>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>