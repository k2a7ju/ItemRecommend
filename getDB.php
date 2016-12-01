<html>
<head><title>PHP TEST</title></head>
<body>
<div></div>
    <?php

    $mecab = new MeCab_Tagger();

    $dsn = 'mysql:dbname=tweetdata_europe_2016;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';

    try{
        $dbh = new PDO($dsn, $user, $password);

        $sql = 'select id,tweet_text from tweetdata_Europe_2016 where lang="ja"';
        foreach ($dbh->query($sql) as $row) {

           $str = $row['tweet_text'];
           $str = mb_convert_kana($str,'rn');

           $str = split("http",$str)[0];
           $str = str_replace("#","",$str);
           $str = str_replace("＃","",$str);
           $str = str_replace("@","",$str);
           $str = str_replace(":","",$str);
           $str = str_replace("/","",$str);
           $str = str_replace(".","",$str);
           $str = str_replace(",","",$str);
           $str = str_replace("!","",$str);
           $str = str_replace("(","",$str);
            $str = str_replace(")","",$str);
            $str = str_replace(";","",$str);
            $str = str_replace("-","",$str);
            $str = str_replace("","",$str);
            $str = str_replace("'","",$str);
            $str = str_replace("[","",$str);
            $str = str_replace("]","",$str);
            $str = str_replace("?","",$str);
            $str = str_replace("\"","",$str);
            $str = str_replace("⋯","",$str);
            $str = str_replace("ー","",$str);
            $str = str_replace("❤","",$str);
            $str = str_replace("_","",$str);
            $str = str_replace("^","",$str);
            $str = str_replace("%","",$str);
            $str = str_replace("o","",$str);
            $str = str_replace("o","",$str);
            $str = str_replace("°","",$str);
            $str = str_replace("♪","",$str);

            echo "<pre>";
            echo $str."<br>";
            $node = $mecab->parseToNode($str);
            while($node = $node->getNext()){
                if(strpos($node->feature,'名詞') !== false){
                    echo "   ";
                    $fe = $node->getSurface().",".$node->feature;
                    echo $fe;
                    echo "<br>";
                    $insert = "INSERT INTO tfidf_japan (master_id, tweet_text, analyze_result) VALUES (:master_id, :tweet_text, :analyze_result)";
                    $stmt = $dbh -> prepare($insert);
                    $stmt->bindParam(':master_id', $row['id'], PDO::PARAM_INT);
                    $stmt->bindParam(':tweet_text', $row['tweet_text'], PDO::PARAM_STR);
                    $stmt->bindParam(':analyze_result', $fe, PDO::PARAM_STR);
                    $stmt->execute();
                }            
            }
            echo "</pre>";
            echo "<br>";
        }
    }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }

    $dbh = null;

    ?>

</body>
</html>