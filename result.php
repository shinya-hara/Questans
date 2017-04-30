<?php
// エスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <title>結果 | アンケートシステム</title>
    <style type="text/css">
		</style>
  </head>
  <body>
    <div class="container">
      <h1>アンケートシステム</h1><hr>
      <h2>アンケート結果</h2>
      <?php
      echo "<p>".
           "設問1：".$_POST['q1']."<br>".
           "設問2：".$_POST['q2']."<br>".
           "設問3：".$_POST['q3']."<br>".
           "設問4：".$_POST['q4']."<br>".
           "設問5：".$_POST['q5']."<br>".
           "</p>";
      echo "<p>".
           "回答1：".$_POST['a1']."<br>".
           "回答2：".$_POST['a2']."<br>".
           "回答3：".$_POST['a3']."<br>".
           "回答4：".$_POST['a4']."<br>".
           "回答5：".$_POST['a5']."<br>".
           "</p>";
      ?>
      <div id="chart" style="min-width: 400px; max-width: 600px; height: 400px; margin: 0 auto"></div>
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
          categories: ['設問1', '設問2', '設問3', '設問4', '設問5'],
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