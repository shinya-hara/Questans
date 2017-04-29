
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
      <form method="post" action="#">
      <h2>設定</h2>
      <div class="form-group">
        <label for="q1">設問1</label>
        <textarea class="form-control" id="q1" name="q1" rows="3" placeholder="設問1の内容"></textarea>
      </div>
      <div class="form-group">
        <label for="q2">設問2</label>
        <textarea class="form-control" id="q2" name="q2" rows="3" placeholder="設問2の内容"></textarea>
      </div>
      <div class="form-group">
        <label for="q3">設問3</label>
        <textarea class="form-control" id="q3" name="q3" rows="3" placeholder="設問3の内容"></textarea>
      </div>
      <div class="form-group">
        <label for="q4">設問4</label>
        <textarea class="form-control" id="q4" name="q4" rows="3" placeholder="設問4の内容"></textarea>
      </div>
      <div class="form-group">
        <label for="q5">設問5</label>
        <textarea class="form-control" id="q5" name="q5" rows="3" placeholder="設問5の内容"></textarea>
      </div>
      
      <h2>回答</h2>
        <table class="table table-striped text-center">
          <thead>
            <tr>
              <th class="text-center">設問</th>
              <th class="text-center">選択肢1</th>
              <th class="text-center">選択肢2</th>
              <th class="text-center">選択肢3</th>
              <th class="text-center">選択肢4</th>
              <th class="text-center">選択肢5</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>設問1</td>
              <td><input type="radio" name="a1" value="選択肢1"></td>
              <td><input type="radio" name="a1" value="選択肢2"></td>
              <td><input type="radio" name="a1" value="選択肢3" checked></td>
              <td><input type="radio" name="a1" value="選択肢4"></td>
              <td><input type="radio" name="a1" value="選択肢5"></td>
            </tr>
            <tr>
              <td>設問2</td>
              <td><input type="radio" name="a2" value="選択肢1"></td>
              <td><input type="radio" name="a2" value="選択肢2" checked></td>
              <td><input type="radio" name="a2" value="選択肢3"></td>
              <td><input type="radio" name="a2" value="選択肢4"></td>
              <td><input type="radio" name="a2" value="選択肢5"></td>
            </tr>
            <tr>
              <td>設問3</td>
              <td><input type="radio" name="a3" value="選択肢1"></td>
              <td><input type="radio" name="a3" value="選択肢2"></td>
              <td><input type="radio" name="a3" value="選択肢3"></td>
              <td><input type="radio" name="a3" value="選択肢4"></td>
              <td><input type="radio" name="a3" value="選択肢5" checked></td>
            </tr>
            <tr>
              <td>設問4</td>
              <td><input type="radio" name="a4" value="選択肢1" checked></td>
              <td><input type="radio" name="a4" value="選択肢2"></td>
              <td><input type="radio" name="a4" value="選択肢3"></td>
              <td><input type="radio" name="a4" value="選択肢4"></td>
              <td><input type="radio" name="a4" value="選択肢5"></td>
            </tr>
            <tr>
              <td>設問5</td>
              <td><input type="radio" name="a5" value="選択肢1"></td>
              <td><input type="radio" name="a5" value="選択肢2"></td>
              <td><input type="radio" name="a5" value="選択肢3"></td>
              <td><input type="radio" name="a5" value="選択肢4" checked></td>
              <td><input type="radio" name="a5" value="選択肢5"></td>
            </tr>
          </tbody>
        </table>
        <input class="btn btn-primary btn-block" type="submit" value="Submit">
      </form>
    </div><!-- container -->
    
    <div class="container">
    <?php
      if (isset($_POST["q1"]) &&
          isset($_POST["q2"]) &&
          isset($_POST["q3"]) &&
          isset($_POST["q4"]) &&
          isset($_POST["q5"]) 
          ) {
        echo "<h2>送信結果</h2>";
        echo "<h3>設問内容</h3>";
        echo "<p>".
             "設問1：".$_POST["q1"]."<br>".
             "設問2：".$_POST["q2"]."<br>".
             "設問3：".$_POST["q3"]."<br>".
             "設問4：".$_POST["q4"]."<br>".
             "設問5：".$_POST["q5"]."<br>".
             "</p>";
        echo "<h3>回答結果</h3>";
        echo "<p>".
             "回答1：".$_POST["a1"]."<br>".
             "回答2：".$_POST["a2"]."<br>".
             "回答3：".$_POST["a3"]."<br>".
             "回答4：".$_POST["a4"]."<br>".
             "回答5：".$_POST["a5"]."<br>".
             "</p>";
      }
    ?>
    </div>
  </body>
</html>