<html lang="ja">
<head>
        <meta charset="UTF-8">
        <title>Top Page</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <div id="header-fixed">
        <div id="header-bk">
            <div id="header">Cycle Course Reviewer</div>
        </div>
    </div>

<body>
<?php
    session_start();
    if(!isset($_SESSION["name"])){
        $no_login_url = "login.php";
        header("Location: {$no_login_url}");
        exit;
    }
?>
    <h2>TOP PAGE</h2>
    <br>
    (コース一覧)
    <br>
    <h3>
<?php
//DB接続
    $dsn = 'mysql:dbname=tb210133db;host=localhost';
    $user = 'tb-210133';
    $password = 'PHF7gy2jzx';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 

//コース表示
    $sql = 'select*from courseDB';
    $stmt = $pdo -> query($sql);
    $result = $stmt -> fetchAll();
    foreach($result as $r){
?>
        <a href="course.php?courseId=<?php echo $r['courseId'];?>"><?php echo $r['courseName'];?></a><br>
<?php
        echo $r["start"]." - ".$r["goal"]."  ".$r["distance"]."km ";
        for($i=0; $i<$r["difficulty"]; $i++){
            echo "★";
        }
        echo "<hr>";
    }
    
    unset($pdo);
?>
    </h3>

    <footer>
        <a href="course_up.php">コースの登録はこちら→</a>
        <hr>
        <a href="search.php">検索はこちら→</a>
    </footer>
</body>
</html>