<html lang="ja">
<head>
        <meta charset="UTF-8">
        <title>Course</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <div id="header-fixed">
        <div id="header-bk">
            <div id="header">Cycle Course Reviewer</div>
        </div>
    </div>

<body>
<?php
//二重送信防止
    if($_SERVER['REQUEST_METHOD']==='POST'){   
        header('Location:course.php');
    }

//DB接続
    $dsn = 'mysql:dbname=tb210133db;host=localhost';
    $user = 'tb-210133';
    $password = 'PHF7gy2jzx';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 

//スレッドIDの取得
    $c_id = $_GET["courseId"];
    
    if(!preg_match("/[0-9]/",$c_id)){
        echo "不正な値です<br>";
    }elseif(preg_match("/[0-9]/",$c_id)){
        $sql = 'select courseName from courseDB where courseId=:courseId';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':courseId', $c_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt -> fetch();

        $t_com="🚲　".$result[0]."　🚲";
    }
?>

<!--スレッドのタイトル表示-->
    <h2><?php echo $t_com;?></h2>
    <br>
    <br>
    <div>コメント</div>
    <hr>
<?php
//コメント投稿
    if(isset($_GET["submit"])){
        if(!empty($_GET["name"]) && !empty($_GET["comment"])){
            $name=htmlspecialchars($_GET["name"]);
            $comment = htmlspecialchars($_GET["comment"]);
            $userIp=getenv("REMOTE_ADDR");
            $date = date("Y/m/d G:i:s");
    
            $sql = $pdo -> prepare("INSERT INTO commentDB (name, comment, date, courseId, userIp) VALUES (:name, :comment, :date, :courseId, :userIp)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':courseId', $c_id, PDO::PARAM_INT);
            $sql -> bindParam(':userIp', $userIp, PDO::PARAM_INT);
            $sql -> execute();
        }
    }

//画像/動画を表示する．
    $sql = "SELECT * FROM mediaDB where courseId=:courseId";
    $stmt = $pdo -> prepare($sql);
    $stmt->bindParam(':courseId', $c_id, PDO::PARAM_INT);
    $stmt->execute();
    while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){   
    //動画と画像で場合分け
        $target = $row["fname"];
        if($row["extension"] == "mp4"){
?>
            <video src="import_media.php?target=$target" width="426" height="240" controls></video>
<?php        
        }
        elseif($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif"){
?>
            <img src='import_media.php?target=$target'>
<?php
        }
    }
    echo "<br>";

//コメントの表示
    $sql = 'select*from commentDB where courseId=:courseId';
    $stmt = $pdo -> prepare($sql);
    $stmt->bindParam(':courseId', $c_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt -> fetchAll();
    $i = 1;
    foreach($result as $row){
        echo $i.":".$row['name'].":".$row['date']."<br>";
        echo $row['comment']."<br><br>";
        echo "<hr>";
        $i=$i+1;
    }

    unset($pdo);
?>
<form method="GET" action="course.php">
    
    名前:<input type="text" name="name"><br>
    コメント<br>
    <textarea name="comment" rows="7" cols="70"></textarea><br>
    <input type="hidden" name="courseId" value=<?php echo $c_id;?>>
    <input type="submit" name="submit" value="送信"><br>
    <br>
</form>

<form method="POST" enctype="multipart/form-data" action="image_up.php"> 
    画像/動画アップロード<br>
    <input type="file" name="upfile" ><br>
    <input type="hidden" name="courseId" value=<?php echo $c_id;?>><br>
    <input type="submit" value="アップロード">
    ※画像はjpeg方式，png方式，gif方式に対応しています．動画はmp4方式のみ対応しています．<br>
</form>
<hr>
<a href="toppage.php">トップページに戻る</a>
<body>
</html>