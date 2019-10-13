<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Search</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <div id="header-fixed">
        <div id="header-bk">
            <div id="header">Cycle Course Reviewer</div>
        </div>
    </div>

    <body>
    <h3>
<?php
//DB接続
    $dsn = 'db';
    $user = 'user';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 

//検索機能
    //キーワード検索
    if(!empty($_POST["search"])){

        $search = "%".htmlspecialchars($_POST["search"])."%";
        $sql = 'select * from courseDB where concat(courseName,start,goal) like :search';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt -> fetchAll();

    //検索結果表示
        foreach($result as $r){
?>
            <a href="course.php?courseId=<?php echo $r['courseId']?>"><?php echo $r['courseName']?></a><br>
<?php
            echo $r["start"]." - ".$r["goal"]."  ".$r["distance"]."km ";
            for($i=0; $i<$r["difficulty"]; $i++){
                echo "★";
            }
            echo "<br><br>";
        }
        echo "<hr>";
    }

    //難易度検索
    if(isset($_POST["dif"])){
    
        $dif=$_POST["dif"];
        $sql = 'select * from courseDB where difficulty=:dif';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dif', $dif, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt -> fetchAll();

        //検索結果表示
        foreach($result as $r){
?>
            <a href="course.php?courseId=<?php echo $r['courseId']?>"><?php echo $r['courseName']?></a><br>
<?php
            echo $r["start"]." - ".$r["goal"]."  ".$r["distance"]."km ";
            for($i=0; $i<$r["difficulty"]; $i++){
                echo "★";
            }
            echo "<br><br>";
        }
        echo "<hr>";
    }

    unset($pdo);
?>
    </h3>

    キーワードで検索<br>
    <form method="POST" action="search.php">
        キーワードを入力<input type="text" name="search"><br>
        <input type="submit" value="検索"><br>
    </form>
    <hr>

    難易度で検索<br>
    <form method="POST" action="search.php">
    <p>難易度
        <input type="radio" name="dif" value="1">★
        <input type="radio" name="dif" value="2">★★
        <input type="radio" name="dif" value="3">★★★
        <input type="radio" name="dif" value="4">★★★★
        <input type="radio" name="dif" value="5">★★★★★
    </p>
        <input type="submit" name="submit" value="検索">
    </form>
    <hr>
    <br>
    <a href="toppage.php">トップページへ戻る→</a><br>
    </body>
</html>
