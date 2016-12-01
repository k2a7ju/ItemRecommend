<html>
<head><title></title></head>
<body>

  <?php

  $mecab = new MeCab_Tagger();

  $dsn = 'mysql:dbname=tweetdata_europe_2016;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';

  try{
    $dbh = new PDO($dsn, $user, $password);
    $sql = 'select analyze_result from tfidf_japan';
    foreach ($dbh->query($sql) as $row){
      $str = $row['analyze_result'];
      $str = split(",",$str)[0];
      $array[] = $str;
    }
    $count = count($array);
    echo '総文字数: '.$count;
    $output = array_count_values($array);
    arsort($output);
    foreach ($output as $key => $value){
      $output[$key] = $value / $count;
    }

    echo "<pre>";
    print_r($output);
    echo "</pre>";
  }catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
  }

  $dbh = null;

  ?>
  <!-- http://chalow.net/2005-10-12-1.html -->

</body>
</html>
