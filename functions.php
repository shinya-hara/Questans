<?php
// エスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// 文字列の前後の半角空白と全角空白を削除する関数
function trim_emspace($str) {
  // 先頭の半角、全角スペースを、空文字に置き換える
  $str = preg_replace('/^[ 　]+/u', '', $str);
  // 最後の半角、全角スペースを、空文字に置き換える
  $str = preg_replace('/[ 　]+$/u', '', $str);
  return $str;
}