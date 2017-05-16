<?php
// DB接続のための諸情報
$dsn = 'mysql:dbname=questionnarie;host=localhost;charset=utf8';
$user = 'dbuser';
$password = 'dbpass';

$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $dbh = new PDO($dsn, $user, $password);
  echo "接続したぞ<br>";
  $sql = 'select questions.* from questions where questions.q_id in (select questionnaries.q_id from questionnaries);';
  foreach ($dbh->query($sql) as $row) {
    echo $row['q_id']."\t";
    echo $row['q_num']."\t";
    echo $row['question']."<br>";
  }
  // $stmt = $dbh->prepare("insert into questions (q_id, q_num, question) values (?, ?, ?)");
  // $stmt->bindParam(1, $q_id);
  // $stmt->bindParam(2, $q_num);
  // $stmt->bindParam(3, $question);
  // $q_id = 1;
  // $q_num = 4;
  // $question = '質問4質問4質問4';
  // $stmt->execute();
  
  // $dbh->exec("insert into questionnaries (title, created) values ('title5', '2017-5-13 12:26:00')");
  // $id = $dbh->lastInsertId();
  // echo 'last inserted id is '.$id;
  
  // $stmt = $dbh->prepare("insert into questionnaries (title, created) values (?, ?)");
  // $stmt->bindValue(1, )
} catch (PDOException $e){
  echo 'Connection failed:'.$e->getMessage();
  die();
}




// DBへの接続を明示的に閉じる場合
// （プログラム終了時には自動的に接続が閉じられる）
// $dbh = null;
// echo "閉じたぞ";