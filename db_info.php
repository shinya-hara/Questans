<?php
// DB接続のための諸情報
// $dsn = 'mysql:dbname=questionnaire;host=localhost;charset=utf8';
// $dsn = 'mysql:dbname=questans;unix_socket=/cloudsql/questans-197808:asia-northeast1:questans';
// $dsn = 'mysql:host=127.0.0.1:3306;dbname=questans;charset=utf8'
$dsn = getenv('MYSQL_DSN');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
