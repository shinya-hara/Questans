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
      $_SESSION['flash_msg'] = "ようこそ，".$username."さん．";
      $_SESSION['flash_flag'] = true;
      // ユーザ名をセット
      $_SESSION['username'] = $username;
      // ログイン完了後に / に遷移
      header('Location: /management.php');
      exit;
    }
    // 認証が失敗したとき
    // 「403 Forbidden」
    http_response_code(403);
  }
  header('Content-Type: text/html; charset=UTF-8');
  ?>
  <!DOCTYPE html>
  <title>ログインページ</title>
  <h1>ログインしてください</h1>
  <form method="post" action="">
    ユーザ名: <input type="text" name="username" value="">
    パスワード: <input type="password" name="password" value="">
    <input type="hidden" name="token" value="<?=h(generate_token())?>">
    <input type="submit" value="ログイン">
  </form>
  <p>ユーザ名：user パスワード：user でテストユーザとしてログイン可能</p>
  <?php if (http_response_code() === 403): ?>
    <p style="color: red;">ユーザ名またはパスワードが違います</p>
  <?php endif; ?>