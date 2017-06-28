<?php
require_once __DIR__ . '/db_info.php';
require_once __DIR__ . '/functions.php';
require_logined_session();
try {
  $dbh = new PDO($dsn, $user, $password,
                 [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_EMULATE_PREPARES => false ]);
  try {
    // アンケート情報の取得
    $stmt = $dbh->prepare("select title,created,updated,owner,user_name from questionnaires,users where q_id = ? && owner = user_id");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questionnaires = $stmt->fetch();
    // 質問を取得
    $stmt = $dbh->prepare("select * from questions where q_id = ? order by q_num");
    $stmt->bindValue(1, $_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll();
    $qCount = count($questions);  // 質問数
    // 選択肢を取得
    $stmt = $dbh->prepare("select * from choices where q_id = ? order by c_num");
    $stmt->bindValue(1, $_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $choices = $stmt->fetchAll();
    $cCount = count($choices);  // 選択肢数
    // アンケートの回答数をカウント
    $stmt = $dbh->prepare("select count(*) from answers where q_id = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT);
    $stmt->execute();
    $answeredCount = $stmt->fetchColumn();
    // 回答結果を取得
    $stmt = $dbh->prepare("SELECT count(answer) FROM answers AS a, ans_detail AS d WHERE a.ans_id = d.ans_id AND q_id = ? AND q_num = ? AND answer = ?");
    $stmt->bindValue(1, (int)$_POST['q_id'], PDO::PARAM_INT); // アンケートIDをbind
    for ($i = 0; $i < $qCount; $i++) {
      $stmt->bindValue(2, (int)$i+1, PDO::PARAM_INT);   // 質問番号をbind
      for ($j = 0; $j < $cCount; $j++) {
        $stmt->bindValue(3, (int)$j+1, PDO::PARAM_INT); // 選択肢をbind
        $stmt->execute();
        $result = $stmt->fetch();
        $results_array[$j][$i] = $result['count(answer)'];  // 二次元配列に結果を格納
      }
    }
    // グラフ作成用のデータを格納しJSON形式でJavaScriptに渡す
    $chart_data = [];
    for ($i = 0; $i < $cCount; $i++) {
      $chart_data[$i] = ['name'=>$choices[$cCount-$i-1]['choice'], 'data'=>$results_array[$cCount-$i-1]];
    }
    $jsonData = json_encode($chart_data);
    // 質問番号のラベルデータを格納しJSON形式でJavaScriptに渡す
    $categories = [];
    for ($i = 0; $i < $qCount; $i++) {
      $categories[$i] = '質問'.($i+1);
    }
    $jsonCategories = json_encode($categories);
    
  } catch (PDOException $e) {
    $_SESSION['status'] = "danger";
    $_SESSION['flash_msg'] = "アンケート一覧の取得に失敗しました．";
    $_SESSION['flash_flag'] = true;
  }
} catch (PDOException $e) {
  $_SESSION['status'] = "danger";
  $_SESSION['flash_msg'] = "データベースの接続に失敗しました．";
  $_SESSION['flash_flag'] = true;
}
?>
<?php include __DIR__.'/flash.php'; ?>
<?php include __DIR__.'/contentHeader.php'; ?>
<div class="container">
  <button type="button" class="btn btn-default" id="back" data-id="<?=(int)$_POST['q_id']?>">戻る</button>
  
  <h3>結果</h3>
  <table class="table text-center">
    <thead>
      <tr>
        <th class="text-center td-num none">番号</th>
        <th>質問</th>
        <?php for ($i = 0; $i < $cCount; $i++): ?>
        <th class="text-center"><?=h($choices[$i]['choice'])?></th>
        <?php endfor; ?>
      </tr>
    </thead>
    <tbody>
      <?php for ($i = 0; $i < $qCount; $i++): ?>
      <tr>
        <td class="td-num none"><?=$i+1?></td>
        <td class="text-left"><?=h($questions[$i]['question'])?></td>
        <?php for ($j = 0; $j < $cCount; $j++): ?>
        <td><?=$results_array[$j][$i]?></td>
        <?php endfor; ?>
      </tr>
      <?php endfor; ?>
    </tbody>
  </table>
  <div id="chart"></div>
  
</div>
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    // 詳細に戻る
    $('#back').on('click', function() {
      $.post('detail.php',
      {
        'q_id': $(this).attr('data-id')
      },
      function(data) {
        $('main').html(data);
      });
    });
    // ユーザページ
    $('span.owner').on('click', function() {
      $.post('user.php',
      {
        'req_user_id': <?=$questionnaires['owner']?>
      },
      function(data) {
        $('main').html(data);
      });
    });
    // グラフ表示
    var chartData = $.parseJSON('<?=$jsonData?>'.replace(/(\r\n)/g, '\\n'));
    var xAxisCategories = $.parseJSON('<?=$jsonCategories?>'.replace(/(\r\n)/g, '\\n'));
    Highcharts.setOptions({
      // colors: ['#ff7f7f', '#ffbf7f', '#7fff7f', '#7fffff', '#bf7fff']
      // colors: ['#ff3300', '#ffff66', '#66ff66', '#99ccff', '#cc99ff']
      // colors: ['#247BA0', '#6EA4BF', '#C2EFEB', '#3E517A', '#120D31']
      // colors: ['#e4c4db', '#71b174', '#b2b6db', '#b4cb32', '#44aeea']
      colors: ['#4eacb8', '#b9b327', '#147472', '#b80040', '#1d518b']
    });
    Highcharts.chart('chart', {
      chart: {
        type: 'bar'
      },
      title: {
        text: 'アンケート結果'
      },
      xAxis: {
        categories: xAxisCategories
      },
      yAxis: {
        min: 0,
        title: {
          text: '割合 (%)'
        },
        reversedStacks: true,
        minTickInterval: 10
      },
      // tooltip: {
      //   pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
      //   shared: true
      // },
      legend: {
        reversed: true
      },
      plotOptions: {
        series: {
          stacking: 'percent',
          dataLabels: {
            enabled: true,
            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
            formatter: function() {
              if (this.y != 0) {
                return Math.round(this.percentage)+' %';
              }
            }
          }
        }
      },
      series: chartData
    });
  });
</script>
