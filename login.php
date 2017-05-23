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
    // ログイン完了後に /management.php に遷移
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
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>ログイン | アンケートシステム</title>
    <style>
    body{
      /*background: #eee url(img/bg.png);*/
    }
    html,body{
      position: relative;
      height: 100%;
    }
    .container {
      /*height: 100%;*/
      /*background-color: #ecf0f1;*/
    }

.login-container{
    position: relative;
    width: 300px;
    margin: 80px auto;
    padding: 20px 40px 40px;
    text-align: center;
    background: #fff;
    border: 1px solid #ccc;
}

#output{
    position: absolute;
    width: 300px;
    top: -75px;
    left: 0;
    color: #fff;
}

#output.alert-success{
    background: rgb(25, 204, 25);
}

#output.alert-danger{
    background: rgb(228, 105, 105);
}


.login-container::before,.login-container::after{
    content: "";
    position: absolute;
    width: 100%;height: 100%;
    top: 3.5px;left: 0;
    background: #fff;
    z-index: -1;
    -webkit-transform: rotateZ(4deg);
    -moz-transform: rotateZ(4deg);
    -ms-transform: rotateZ(4deg);
    border: 1px solid #ccc;

}

.login-container::after{
    top: 5px;
    z-index: -2;
    -webkit-transform: rotateZ(-2deg);
     -moz-transform: rotateZ(-2deg);
      -ms-transform: rotateZ(-2deg);

}

.avatar{
    background-image: url("img/user.png");
    width: 100px;height: 100px;
    margin: 10px auto 30px;
    border-radius: 100%;
    border: 2px solid #aaa;
    background-size: cover;
}

.form-box input{
    width: 100%;
    padding: 10px;
    text-align: center;
    height:40px;
    border: 1px solid #ccc;;
    background: #fafafa;
    transition:0.2s ease-in-out;

}

.form-box input:focus{
    outline: 0;
    background: #eee;
}

.form-box input[type="text"]{
    border-radius: 5px 5px 0 0;
    text-transform: lowercase;
}

.form-box input[type="password"]{
    border-radius: 0 0 5px 5px;
    border-top: 0;
}

.form-box button.login{
    margin-top:15px;
    padding: 10px 20px;
}

    </style>
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
      
      <div class="login-container">
        <div id="output"></div>
        <div class="avatar"></div>
        <div class="form-box">
          <form action="" method="post">
            <input name="username" type="text" placeholder="username">
            <input name="password" type="password" placeholder="password">
            <input type="hidden" name="token" value="<?=h(generate_token())?>">
            <button class="btn btn-info btn-block login" type="submit">Login</button>
          </form>
        </div>
      </div>
      
      <p>ユーザ名：user パスワード：user でテストユーザとしてログイン可能</p>
      <?php if (http_response_code() === 403): ?>
        <p style="color: red;">ユーザ名またはパスワードが違います</p>
      <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  </body>
</html>