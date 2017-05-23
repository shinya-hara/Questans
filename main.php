<?php
require_once __DIR__.'/functions.php';
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"])) {
  header("Location: logout.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>メイン</title>
</head>
<body>
  <h1>メイン画面</h1>
  <!-- ユーザー名にHTMLタグが含まれても良いようにエスケープする -->
  <p>ようこそ<u><?= h($_SESSION["NAME"]); ?></u>さん</p>
  <ul>
    <li><a href="logout.php">ログアウト</a></li>
  </ul>
</body>
</html>