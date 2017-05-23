<?php
require_once __DIR__.'/functions.php';
require_logined_session();
$cnt = 0;
// セッション変数に格納されている質問を配列に格納
for ($i = 1; $i <= $_SESSION['q_num']; $i++) {
  $questions[] = $_SESSION['q'.$i];
}
// セッション変数に格納されている選択肢を配列に格納
for ($i = 1; $i <= $_SESSION['c_num']; $i++) {
  $choices[] = $_SESSION['c'.$i];
}
// json形式に変換
$jsonQs = json_encode($questions);
$jsonCs = json_encode($choices);
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
          <!--<div class="help-block with-errors"></div>-->
        </div>
        <div class="row">
          <div class="col-sm-6">
            <input type="button" value="Add new question" class="btn btn-info center-block" id="addQBtn">
          </div>
          <div class="col-sm-6">
            <input type="button" value="Add new choice" class="btn btn-info center-block" id="addCBtn">
          </div>
        </div>
        <div class="form-group">
          <input class="btn btn-primary btn-block" type="submit" value="<?=$_SESSION['update']==1 ? '更新' : '作成'?>">
        </div>
        <input type="hidden" name="q_num" value='0'><!-- 質問数 -->
        <input type="hidden" name="c_num" value='0'><!-- 選択肢数 -->
      </form>
      <?php include 'debug.php'; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script>
    $(function() {
      'use strict';
      
      // 削除ボタンの属性を変更する
      // num: 押された削除ボタンの質問番号
      function q_update(num) {
        if (num >= q_cnt) {
          q_cnt--;
          $('input[name="q_num"]').attr('value', q_cnt);
          return;
        }
        $('#q'+(num+1)+'-group').attr('id', 'q'+num+'-group');
        $('#q'+num+'-group>label').attr('for', 'q'+num).text('質問'+num);
        
        $('#delQBtn'+(num+1)).attr({
          'id': 'delQBtn'+num,
          'value': 'Delete Q'+num
        });
        $('#q'+(num+1)).attr({
          'id': 'q'+num,
          'name': 'q'+num,
          'placeholder': '質問'+num+'の内容'
        });
        $('#delQBtn'+num).off();
        $('#delQBtn'+num).on('click', { num: num }, delTextarea);
        q_update(num+1);
      }
      // テキストエリアを削除する
      var delTextarea = function(e) {
        $('#q'+e.data.num+'-group').remove();
        q_update(e.data.num);
      }
      
      // テキストエリアを増減させるボタンを設置
      // jQueryにcloneという便利そうなメソッドを発見(2017/5/16)
      var q_cnt = 0;  // 質問数
      $('#addQBtn').on('click', function () {
        q_cnt++;
        var html = '<div class="form-group has-feedback" id="q'+q_cnt+'-group">\
                    <div class="form-group"><input type="button" name="del" value="Delete Q'+q_cnt+'" class="btn btn-danger btn-xs pull-right" id="delQBtn'+q_cnt+'" tabindex="-1"></div>\
                    <label for="q'+q_cnt+'" class="control-label">質問'+q_cnt+'</label>\
                    <textarea class="form-control" id="q'+q_cnt+'" name="q'+q_cnt+'" rows="3" placeholder="質問'+q_cnt+'の内容" data-error="質問を入力してください．不要な場合は削除してください．" required></textarea>\
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\
                    <!--<div class="help-block with-errors"></div>-->\
                    </div>';
        $('#addQBtn').before(html);
        $('input[name="q_num"]').attr('value', q_cnt);
        $('#delQBtn'+q_cnt).on('click', { num: q_cnt }, delTextarea);
        $('#q'+q_cnt).focus();
        $('form').validator('update');
      });
      
      // 削除ボタンの属性を変更する
      // num: 押された削除ボタンの選択肢番号
      function c_update(num) {
        if (num >= c_cnt) {
          c_cnt--;
          $('input[name="c_num"]').attr('value', c_cnt);
          return;
        }
        $('#c'+(num+1)+'-group').attr('id', 'c'+num+'-group');
        $('#c'+num+'-group>label').attr('for', 'c'+num).text('選択肢'+num);
        
        $('#delCBtn'+(num+1)).attr({
          'id': 'delCBtn'+num,
          'value': 'Delete C'+num
        });
        $('#c'+(num+1)).attr({
          'id': 'c'+num,
          'name': 'c'+num,
          'placeholder': '質問'+num+'の内容'
        });
        $('#delCBtn'+num).off();
        $('#delCBtn'+num).on('click', { num: num }, delChoice);
        c_update(num+1);
      }
      // テキストエリアを削除する
      var delChoice = function(e) {
        $('#c'+e.data.num+'-group').remove();
        c_update(e.data.num);
      }
      // 選択肢
      var c_cnt = 0;  // 選択肢数
      $('#addCBtn').on('click', function () {
        c_cnt++;
        var html = '<div class="form-group has-feedback" id="c'+c_cnt+'-group">\
                    <div class="form-group"><input type="button" name="del" value="Delete C'+c_cnt+'" class="btn btn-danger btn-xs pull-right" id="delCBtn'+c_cnt+'" tabindex="-1"></div>\
                    <label for="c'+c_cnt+'" class="control-label">選択肢'+c_cnt+'</label>\
                    <textarea class="form-control" id="c'+c_cnt+'" name="c'+c_cnt+'" rows="3" placeholder="選択肢'+c_cnt+'の内容" data-error="選択肢を入力してください．不要な場合は削除してください．" required></textarea>\
                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>\
                    <!--<div class="help-block with-errors"></div>-->\
                    </div>';
        $('#addCBtn').before(html);
        $('input[name="c_num"]').attr('value', c_cnt);
        $('#delCBtn'+c_cnt).on('click', { num: c_cnt }, delChoice);
        $('#c'+c_cnt).focus();
        $('form').validator('update');
      });
      
      // ajax.phpから「修正」を選択して遷移してきた場合
      if (/ajax\.php$/.test(document.referrer)) {
        // json形式の質問をパースし格納
        var question = JSON.parse('<?=$jsonQs?>');
        var choice = JSON.parse('<?=$jsonCs?>');
        // 前回のフォームの内容を再現
        $('#title').val("<?=$_SESSION['title']?>");
        for (var i = 0, len = question.length; i < len; i++) {
          $('#addQBtn').trigger('click');
          $('#q'+q_cnt).val(question[q_cnt-1]);
        }
        for (var i = 0, len = choice.length; i < len; i++) {
          $('#addCBtn').trigger('click');
          $('#c'+c_cnt).val(choice[c_cnt-1]);
        }
        $('form').validator('validate');
      } else {
        $('#addQBtn').trigger('click');
        $('#addCBtn').trigger('click');
      }
      // ページから離れる際に確認
      var isChanged = false;  // フォームの状態を表すフラグ
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