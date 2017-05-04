<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>アンケートシステム</title>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>質問</h2>
      <div class="form-group">
        <label for="">質問数</label>
        <input id="size" class="form-control" type="number" name="size" value="3" min="1" max="20">
      </div>
      <form method="post" action="#">
        <div id="box">
          <input type="button" name="add" value="Add new question" class="btn btn-info center-block" id="addBtn">
        </div>
        <input class="btn btn-primary btn-block" type="submit" value="Submit">
      </form>
    </div><!-- container -->
    
    <div class="container well">
    <?php
    echo "<h2>送信結果</h2>";
    echo "<h3>質問内容</h3>";
    echo "<p>".
         "質問1：".$_POST["q1"]."<br>".
         "質問2：".$_POST["q2"]."<br>".
         "質問3：".$_POST["q3"]."<br>".
         "質問4：".$_POST["q4"]."<br>".
         "質問5：".$_POST["q5"]."<br>".
         "</p>";
    echo "<h3>回答結果</h3>";
    echo "<p>".
         "回答1：".$_POST["a1"]."<br>".
         "回答2：".$_POST["a2"]."<br>".
         "回答3：".$_POST["a3"]."<br>".
         "回答4：".$_POST["a4"]."<br>".
         "回答5：".$_POST["a5"]."<br>".
         "</p>";
    ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
    // 質問数の入力に応じてテキストエリアを増減させる
    // $('#size')
    //   .change(function() {
    //     var html = "";
    //     var size = $('#size').val();
    //     var pre_size = $('#box>div').length;
    //     var diff = size - pre_size;
    //     if (diff > 0) {
    //       for (var i=pre_size+1; i<=size; i++) {
    //         html += '<div class="form-group">\
    //                   <label for="q'+i+'">質問'+i+'</label>\
    //                   <textarea class="form-control" id="q'+i+'" name="q'+i+'" rows="3" placeholder="質問'+i+'の内容"></textarea>\
    //                 </div>';
    //       }
    //     } else if (diff < 0) {
    //       // diffが0になるまでループ
    //       while (diff++) {
    //         console.log(diff);
    //         $('#box>div:last').remove();
    //       }
    //     }
    //     $('#box').append(html);
    //   })
    //     .change();
    
    // 削除ボタンの属性を変更する
    // num: 押された削除ボタンの質問番号
    function update(num) {
      if (num >= cnt) {
        cnt--;
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
      $('#delBtn'+num).on('click', { num: num }, delTA);
      update(num+1);
    }
    // テキストエリアを削除する
    var delTA = function(e) {
      $('#q'+e.data.num+'-group').remove();
      update(e.data.num);
    }
    
    // テキストエリアを増減させるボタンを設置
    var cnt = 0;  // 質問数
    $('#addBtn').on('click', function () {
      cnt++;
      var html = '<div class="form-group" id="q'+cnt+'-group">\
                  <label for="q'+cnt+'">質問'+cnt+'</label>\
                  <input type="button" name="del" value="Delete Q'+cnt+'" class="btn btn-danger btn-xs pull-right" id="delBtn'+cnt+'">\
                  <textarea class="form-control" id="q'+cnt+'" name="q'+cnt+'" rows="3" placeholder="質問'+cnt+'の内容"></textarea>\
                  </div>';
      $('#addBtn').before(html);
      $('#delBtn'+cnt).on('click', { num: cnt }, delTA);
    });
    $('#addBtn').trigger('click');
    </script>
  </body>
</html>