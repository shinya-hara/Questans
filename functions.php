<?php
/**
* 文字列の前後の半角全角空白を削除する関数
*
* @param string $str
* @return string
*/
function trim_emspace($str) {
  // 先頭の半角、全角スペースを、空文字に置き換える
  $str = preg_replace('/^[ 　]+/u', '', $str);
  // 最後の半角、全角スペースを、空文字に置き換える
  $str = preg_replace('/[ 　]+$/u', '', $str);
  return $str;
}

/**
* ログイン状態によってリダイレクトを行うsession_startのラッパー関数
* 初回時または失敗時にはヘッダを送信してexitする
*/
function require_unlogined_session()
{
  // セッション開始
  @session_start();
  // ログインしていれば /management.php に遷移
  if (isset($_SESSION['username'])) {
    header('Location: /management.php');
    exit;
  }
}
function require_logined_session()
{
  // セッション開始
  @session_start();
  // ログインしていなければ /login.php に遷移
  if (!isset($_SESSION['username'])) {
    header('Location: /login.php');
    exit;
  }
}
// 管理者でのログインを要求
function require_admin_session()
{
  // セッション開始
  @session_start();
  // 管理者権限でログインしていなければ /management.php に遷移
  if ($_SESSION['role']!=1 || !isset($_SESSION['role'])) {
    header('Location: /management.php');
    exit;
  }
}

/**
* CSRFトークンの生成
*
* @return string トークン
*/
function generate_token()
{
  // セッションIDからハッシュを生成
  return hash('sha256', session_id());
}
/**
* CSRFトークンの検証
*
* @param string $token
* @return bool 検証結果
*/
function validate_token($token)
{
  // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
  return $token === generate_token();
}
/**
* htmlspecialcharsのラッパー関数
* nl2br()関数を用いて改行は許可
*
* @param string $str
* @return string
*/
function h($str)
{
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}