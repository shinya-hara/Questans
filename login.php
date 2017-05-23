<?php
// require 'password.php';   // password_verfy()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
require_once __DIR__.'/functions.php';
// セッション開始
session_start();
$db['host'] = "localhost";        // DBサーバのURL
$db['user'] = "dbuser";           // ユーザー名
$db['pass'] = "dbpass";           // ユーザー名のパスワード
$db['dbname'] = "questionnarie";  // データベース名

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
  // 1. ユーザIDの入力チェック
  if (empty($_POST["username"])) {  // emptyは値が空のとき
    $errorMessage = 'ユーザー名が未入力です。';
  } else if (empty($_POST["password"])) {
    $errorMessage = 'パスワードが未入力です。';
  }
  
  if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    // 入力したユーザ名を格納
    $username = $_POST["username"];
    
    // 2. ユーザIDとパスワードが入力されていたら認証する
    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    
    // 3. エラー処理
    try {
      $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
      
      $stmt = $pdo->prepare('SELECT * FROM userData WHERE name = ?');
      $stmt->execute(array($username));
      
      $password = $_POST["password"];
      
      if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $row['password'])) {
          session_regenerate_id(true);
          
          // 入力したIDのユーザー名を取得
          // $id = $row['id'];
          // $sql = "SELECT * FROM userData WHERE id = $id";  //入力したIDからユーザー名を取得
          // $stmt = $pdo->query($sql);
          // foreach ($stmt as $row) {
          //     $row['name'];  // ユーザー名
          // }
          $_SESSION["NAME"] = $row['name'];
          header("Location: main.php");  // メイン画面へ遷移
          exit();  // 処理終了
        } else {
          // 認証失敗
          $errorMessage = 'ユーザー名あるいはパスワードに誤りがあります。';
        }
      } else {
        // 4. 認証成功なら、セッションIDを新規に発行する
        // 該当データなし
        $errorMessage = 'ユーザー名あるいはパスワードに誤りがあります。';
      }
    } catch (PDOException $e) {
      $errorMessage = 'データベースエラー';
      //$errorMessage = $sql;
      // $e->getMessage() でエラー内容を参照可能（デバック時のみ表示）
      echo $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>
</head>
<body>
  <h1>ログイン画面</h1>
  <form id="loginForm" name="loginForm" action="" method="POST">
    <fieldset>
      <legend>ログインフォーム</legend>
      <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
      <label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo h($_POST["username"]);} ?>">
      <br>
      <label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
      <br>
      <input type="submit" id="login" name="login" value="ログイン">
    </fieldset>
  </form>
  <br>
  <form action="signup.php">
    <fieldset>          
      <legend>新規登録フォーム</legend>
      <input type="submit" value="新規登録">
    </fieldset>
  </form>
</body>
</html>