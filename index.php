
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" lang="ja">
    <title>アンケートシステム</title>
  </head>
  <body>
    <h1>アンケートシステム</h1>
    <form method="post" action="#">
      <table>
        <thead>
          <tr>
            <th>設問</th>
            <th>選択肢1</th>
            <th>選択肢2</th>
            <th>選択肢3</th>
            <th>選択肢4</th>
            <th>選択肢5</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>設問1</td>
            <td><input type="radio" name="q1" value="選択肢1"></td>
            <td><input type="radio" name="q1" value="選択肢2"></td>
            <td><input type="radio" name="q1" value="選択肢3" checked></td>
            <td><input type="radio" name="q1" value="選択肢4"></td>
            <td><input type="radio" name="q1" value="選択肢5"></td>
          </tr>
          <tr>
            <td>設問2</td>
            <td><input type="radio" name="q2" value="選択肢1"></td>
            <td><input type="radio" name="q2" value="選択肢2" checked></td>
            <td><input type="radio" name="q2" value="選択肢3"></td>
            <td><input type="radio" name="q2" value="選択肢4"></td>
            <td><input type="radio" name="q2" value="選択肢5"></td>
          </tr>
          <tr>
            <td>設問3</td>
            <td><input type="radio" name="q3" value="選択肢1"></td>
            <td><input type="radio" name="q3" value="選択肢2"></td>
            <td><input type="radio" name="q3" value="選択肢3"></td>
            <td><input type="radio" name="q3" value="選択肢4"></td>
            <td><input type="radio" name="q3" value="選択肢5" checked></td>
          </tr>
          <tr>
            <td>設問4</td>
            <td><input type="radio" name="q4" value="選択肢1" checked></td>
            <td><input type="radio" name="q4" value="選択肢2"></td>
            <td><input type="radio" name="q4" value="選択肢3"></td>
            <td><input type="radio" name="q4" value="選択肢4"></td>
            <td><input type="radio" name="q4" value="選択肢5"></td>
          </tr>
          <tr>
            <td>設問5</td>
            <td><input type="radio" name="q5" value="選択肢1"></td>
            <td><input type="radio" name="q5" value="選択肢2"></td>
            <td><input type="radio" name="q5" value="選択肢3"></td>
            <td><input type="radio" name="q5" value="選択肢4" checked></td>
            <td><input type="radio" name="q5" value="選択肢5"></td>
          </tr>
        </tbody>
      </table>
      <input type="submit" value="Submit">
    </form>
    <h2>送信結果</h2>
    <div>
    <?php
      if (isset($_POST["q1"]) &&
          isset($_POST["q2"]) &&
          isset($_POST["q3"]) &&
          isset($_POST["q4"]) &&
          isset($_POST["q5"]) 
          ) {
        echo "設問1：".$_POST["q1"]."<br>".
             "設問2：".$_POST["q2"]."<br>".
             "設問3：".$_POST["q3"]."<br>".
             "設問4：".$_POST["q4"]."<br>".
             "設問5：".$_POST["q5"];
      }
    ?>
    </div>
  </body>
</html>