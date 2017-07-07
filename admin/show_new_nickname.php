<?php
require_once __DIR__.'/../functions.php';
require_once __DIR__.'/../db_info.php';
require_admin_session();

try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    $stmt = $dbh->prepare("SELECT nickname FROM users WHERE user_id = ?");
    $stmt->execute([$_POST['user_id']]);
    $user = $stmt->fetch();
    if ($user['nickname']==null) {
      echo '（未設定）';
    } else {
      echo h($user['nickname']);
    }
  } catch (PDOException $e) {
    
  }
} catch (PDOException $e) {
  
}