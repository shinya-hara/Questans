<?php
require_once __DIR__.'/functions.php';

session_start();

// セッション変数に質問数を格納
if ($_POST['num']) {
  $_SESSION['num'] = $_POST['num'];
}

// セッション変数に質問を格納
for ($i = 1; $i <= $_SESSION['num']; $i++) {
  if ($_POST['q'.$i]) {
    $_SESSION['q'.$i] = $_POST['q'.$i];
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>結果 | アンケートシステム</title>
    <style type="text/css">
		</style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>アンケート結果</h2>
      <table class="table table-striped">
        <thead>
          <th class="text-center">番号</th><th>質問</th><th>回答</th>
        </thead>
        <tbody>
          <?php for($i = 1; $i <= $_SESSION['num']; $i++): ?>
          <tr>
            <td class="text-center"><?= $i ?></td><td><?= h($_SESSION['q'.$i]) ?></td><td><?= h($_POST['a'.$i]) ?></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      <div id="chart" style="min-width: 400px; max-width: 600px; height: 400px; margin: 0 auto"></div>
      <?php require('debug.php'); ?>
    </div><!-- container -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script>
      Highcharts.chart('chart', {
        chart: {
          polar: true,
          type: 'line'
        },
    
        title: {
          text: 'Answer',
          x: -80
        },
    
        pane: {
          size: '80%'
        },
    
        xAxis: {
          categories: ['質問1', '質問2', '質問3', '質問4', '質問5'],
          tickmarkPlacement: 'on',
          lineWidth: 0
        },
    
        yAxis: {
          gridLineInterpolation: 'polygon',
          lineWidth: 0,
          min: 0
        },
    
        tooltip: {
          shared: true,
          pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
        },
    
        legend: {
          align: 'right',
          verticalAlign: 'top',
          y: 150,
          layout: 'vertical'
        },
    
        series: [{
          name: 'My Answer',
          data: [5, 2, 1, 3, 2],
          pointPlacement: 'on'
        }, {
          name: 'Average Answer',
          data: [4, 5, 3, 5, 4],
          pointPlacement: 'on'
        }]
      });
    </script>
  </body>
</html>