<?php
session_start();
$cnt = 0;
// セッション変数に格納されている質問を配列に格納
for ($i = 1; $i <= $_SESSION['num']; $i++) {
  $questions[] = $_SESSION['q'.$i];
}
// json形式に変換
$jsonQs = json_encode($questions);
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>作成 | アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1>
      <a href="management.php"><input type="button" value="管理画面" class="btn btn-default"></a>
      <hr>
      <h2>作成</h2>
      <form method="post" action="ajax.php" data-toggle="validator" role="form">
        <div class="form-group has-feedback">
          <label for="title" class="control-label">タイトル</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="アンケートのタイトル" data-error="タイトルを入力してください．" required>
          <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
          <div class="help-block with-errors"></div>
        </div>
        <input type="button" name="add" value="Add new question" class="btn btn-info center-block" id="addBtn">
        <div class="form-group">
          <input class="btn btn-primary btn-block" type="submit" value="作成">
        </div>
        <input type="hidden" name="num" value='0'><!-- 質問数 -->
      </form>
      <?php include 'debug.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script>
    $(function() {
      'use strict';
      var isChanged = false;  // フォームの状態を表すフラグ
      // 削除ボタンの属性を変更する
      // num: 押された削除ボタンの質問番号
      function update(num) {
        if (num >= cnt) {
          cnt--;
          $('input[name="num"]').attr('value', cnt);
          return;
        }
        $('#q'+(num+1)+'-group').attr('id', 'q'+num+'-group');
        $('#q'+num+'-group>label').attr('for', 'q'+num).text('質問'+num);
        
        $('#delBtn'+(num+1)).attr({
          'id': 'delBtn'+num,
          'value': 'Delete Q'+num
        });
        $('#q'+(num+1)).attr({
          'id': 'q'+num,
          'name': 'q'+num,
          'placeholder': '質問'+num+'の内容'
        });
        $('#delBtn'+num).off();
        $('#delBtn'+num).on('click', { num: num }, delTextarea);
        update(num+1);
      }
      // テキストエリアを削除する
      var delTextarea = function(e) {
        $('#q'+e.data.num+'-group').remove();
        update(e.data.num);
      }
      
      // テキストエリアを増減させるボタンを設置
      // jQueryにcloneという便利そうなメソッドを発見(2017/5/16)
      var cnt = 0;  // 質問数
      $('#addBtn').on('click', function () {
        cnt++;
        var html = '<div class="form-group has-feedback" id="q'+cnt+'-group">\
                    <div class="form-group"><input type="button" name="del" value="Delete Q'+cnt+'" class="btn btn-danger btn-xs pull-right" id="delBtn'+cnt+'" tabindex="-1"></div>\
                    <label for="q'+cnt+'" class="control-label">質問'+cnt+'</label>\
                    <textarea class="form-control" id="q'+cnt+'" name="q'+cnt+'" rows="3" placeholder="質問'+cnt+'の内容" data-error="質問を入力してください．不要な場合は削除してください．" required></textarea>\
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\
                    <div class="help-block with-errors"></div>\
                    </div>';
        $('#addBtn').before(html);
        $('input[name="num"]').attr('value', cnt);
        $('#delBtn'+cnt).on('click', { num: cnt }, delTextarea);
        $('#q'+cnt).focus();
        $('form').validator('update');
      });
      
      // ajax.phpから「修正」を選択して遷移してきた場合
      if (/ajax\.php$/.test(document.referrer)) {
        // json形式の質問をパースし格納
        var question = JSON.parse('<?=$jsonQs?>');
        // 前回のフォームの内容を再現
        $('#title').val("<?=$_SESSION['title']?>");
        for (var i = 0, len = question.length; i < len; i++) {
          $('#addBtn').trigger('click');
          $('#q'+cnt).val(question[cnt-1]);
        }
        $('form').validator('validate');
      } else {
        // output.phpから遷移してきた場合
        if (/output\.php$/.test(document.referrer)) {
          // セッション変数を解除（空文字列でPOSTできないようにすれば不要？）
        }
        $('#addBtn').trigger('click');
      }
      // ページから離れる際に確認
      $(window).on('beforeunload', function() {
        console.log(isChanged);
        if (isChanged) {
          return "このページを離れようとしています．";
        }
      });
      // フォームに変更があった際に空欄でなければフラグを立てる
      $('form input, form textarea').on('change', function() {
        if ($('form input').val() !== "" || $('form textarea').val() !== "") {
          isChanged = true;
        } else {  // フォームが空欄になったらフラグを落とす
          isChanged = false;
        }
      });
      // このページに遷移後、フォームが空欄でなければフラグを立てる
      if ($('form input').val() !== "" || $('form textarea').val() !== "") {
        isChanged = true;
      }
      $('input[type=submit]').on('click', function() {
        // フォームをサブミットする前にフラグを落とす
        isChanged = false;
      });
    });
    </script>
  </body>
</html>