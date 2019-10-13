<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <div id="header-fixed">
        <div id="header-bk">
            <div id="header">Cycle Course Reviewer</div>
        </div>
    </div>
    
    <body>
    <h2>コース登録</h2>
<?php
    if($_SERVER['REQUEST_METHOD']==='POST'){   
        header('Location:course_up.php');
    }

//DB接続
    $dsn = 'mysql:dbname=tb210133db;host=localhost';
    $user = 'tb-210133';
    $password = 'PHF7gy2jzx';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 

//コース登録
    if(isset($_POST["submit"])){
        if(!empty($_POST["name"])&&!empty($_POST["start"])&&!empty($_POST["goal"])&&!empty($_POST["dis"])&&!empty($_POST["dif"])){
            $name = $_POST["name"];
            $start = $_POST["start"];
            $goal = $_POST["goal"];
            $dis = $_POST["dis"];
            $dif = $_POST["dif"];
            $ip=getenv("REMOTE_ADDR");
    
            $sql = $pdo -> prepare("INSERT INTO courseDB (courseName, start, goal, distance, difficulty, Ip) VALUES (:courseName, :start, :goal, :distance, :difficulty, :Ip)");
            $sql -> bindParam(':courseName', $name, PDO::PARAM_STR);
            $sql -> bindParam(':start', $start, PDO::PARAM_STR);
            $sql -> bindParam(':goal', $goal, PDO::PARAM_STR);
            $sql -> bindParam(':distance', $dis, PDO::PARAM_INT);
            $sql -> bindParam(':difficulty', $dif, PDO::PARAM_INT);
            $sql -> bindParam(':Ip', $ip, PDO::PARAM_STR);
            $sql -> execute();
    
            echo "以下の内容で登録しました<br>";
            echo "コース名:".$name."<br>";
            echo "スタート:".$start."<br>";
            echo "ゴール:".$goal."<br>";
            echo "距離:".$dis."<br>";
            echo "難易度:";
            for($i=0; $i<$dif; $i++){
                echo "★";
            }
            echo "<br>";
    
        }else{
            echo "入力されていない項目があります。<br>";
        }
    }
    unset($pdo);
?>
    <br>
    コースの情報を入力してください<br>
    <form method="POST" action="course_up.php">
        コース名：<input type="text" name="name"><br>
        スタート：<input type="text" name="start"><br>
        ゴール：<input type="text" name="goal"><br>
        距離：<input type="text" name="dis">km<br>
        <p>難易度
        <input type="radio" name="dif" value="1" checked>★
        <input type="radio" name="dif" value="2">★★
        <input type="radio" name="dif" value="3">★★★
        <input type="radio" name="dif" value="4">★★★★
        <input type="radio" name="dif" value="5">★★★★★
        </p><br>
        <input type="submit" name="submit" value="登録">

    </form>
    <hr>
    <a href="toppage.php">トップページへ戻る→</a><br>
    </body>
</html>