<?php
require_once __DIR__.'/functions.php';
session_start();
if (isset($_SESSION["NAME"])) {
  $errorMessage = "ログアウトしました。";
} else {
  $errorMessage = "セッションがタイムアウトしました。";
}
// セッションの変数のクリア
$_SESSION = array();
// セッションクリア
@session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>ログアウト</title>
</head>
<body>
  <h1>ログアウト画面</h1>
  <div><?= h($errorMessage); ?></div>
  <ul>
    <li><a href="login.php">ログイン画面に戻る</a></li>
  </ul>
</body>
</html>