<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_unlogined_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("SELECT * FROM users");
    $stmt->execute();
    // キーがユーザ名、値がパスワードの連想配列を作る
    while ($row = $stmt -> fetch()) {
      $hashes[$row['user_name']]['user_id'] = $row['user_id'];
      $hashes[$row['user_name']]['password'] = $row['password'];
      $hashes[$row['user_name']]['nickname'] = $row['nickname'];
      $hashes[$row['user_name']]['role'] = $row['role'];
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
$userpass = filter_input(INPUT_POST, 'password');
// POSTメソッドのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (
    validate_token(filter_input(INPUT_POST, 'token')) &&
    password_verify(
      $userpass,
      isset($hashes[$username]['password'])
      ? $hashes[$username]['password']
      : '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
      )
    ) {
    // 認証が成功したとき
    // セッションIDの追跡を防ぐ
    session_regenerate_id(true);
    // ログイン完了後にフラッシュメッセージを表示する
    $_SESSION['status'] = "success";
    // $_SESSION['flash_msg'] = "ようこそ，".$username."さん";
    $_SESSION['flash_msg'] = "ようこそ，".$hashes[$username]['nickname']." さん";
    $_SESSION['flash_flag'] = true;
    // ユーザIDをセット
    $_SESSION['user_id'] = $hashes[$username]['user_id'];
    // ユーザ名をセット
    $_SESSION['username'] = $username;
    // 権限情報をセット
    $_SESSION['role'] = $hashes[$username]['role'];
    // ログイン完了後にアクセスしようとしていたURL（もしくはデフォルト値）にリダイレクト
    redirect_back_or('/management.php');
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
    <link rel="stylesheet" href="css/styles.css">
    <title>ログイン | アンケートシステム</title>
  </head>
  <body>
    <header>
      <div class="container clearfix">
        <h1 class="pull-left"><a href="/management.php"><span style="color:#DF0101;">Q</span>uest<span style="color:#DF0101;">a</span>ns</a></h1>
        <div class="buttons">
          <a href="signup.php"><button class="btn btn-primary pull-right" type="button">ユーザ登録</button></a>
          <button class="btn btn-info pull-right" type="button">ログイン</button>
        </div>
      </div>
    </header>
    <div class="container">
      <?php include __DIR__.'/flash.php'; ?>
      <div class="login-container">
        <div class="avatar"></div>
        <div class="form-box">
          <form action="" method="post">
            <input name="username" type="text" placeholder="ユーザ名" class="username">
            <input name="password" type="password" placeholder="パスワード" class="pw2">
            <input type="hidden" name="token" value="<?=h(generate_token())?>">
            <button class="btn btn-info btn-block mtop" type="submit">ログイン</button>
            <div class="text-center">or</div>
            <div class="clearfix">
              <a href="signup.php"><button class="btn btn-primary pull-left signup" type="button">ユーザ登録</button></a>
              <button class="btn btn-warning pull-right guest" type="button">ゲスト</button>
            </div>
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
        $('.guest').on('click', function() {
          $.post(
            'login.php',
            {
              'username': 'guest',
              'password': 'guest',
              'token': '<?=h(generate_token())?>'
            },
            function(data) {
              window.location.href = "/management.php";
            }
          )
        });
      });
    </script>
  </body>
</html>