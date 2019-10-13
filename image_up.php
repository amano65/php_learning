<?php
    $dsn = 'db';
    $user = 'user';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//ファイルアップロードがあったとき
    
    if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){

    //画像・動画をバイナリデータにする
        $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);
        
    //拡張子を見る
        $tmp = pathinfo($_FILES["upfile"]["name"]);
        $extension = $tmp["extension"];
        if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
            $extension = "jpeg";
        }elseif($extension === "png" || $extension === "PNG"){
            $extension = "png";
        }elseif($extension === "gif" || $extension === "GIF"){
            $extension = "gif";
        }elseif($extension === "mp4" || $extension === "MP4"){
            $extension = "mp4";
        }else{
            echo "非対応ファイルです．<br/>";
?>
            <a href="toppage.php">戻る</a><br>
<?php
            exit(1);
        }

    //DBに格納するファイルネーム設定
    //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
        $date = getdate();
        $fname = $_FILES["upfile"]["tmp_name"].$date["year"].$date["mon"].$date["mday"].$date["hours"].$date["minutes"].$date["seconds"];
        $fname = hash("sha256", $fname);
        $c_id = $_POST["courseId"];

    //画像・動画をDBに格納．
        $sql = "INSERT INTO mediaDB(fname, extension, raw_data, courseId) VALUES (:fname, :extension, :raw_data , :courseId);";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindValue(":fname",$fname, PDO::PARAM_STR);
        $stmt -> bindValue(":extension",$extension, PDO::PARAM_STR);
        $stmt -> bindValue(":raw_data",$raw_data, PDO::PARAM_STR);
        $stmt -> bindValue(":courseId",$c_id, PDO::PARAM_INT);
        $stmt -> execute();

        echo "アップロードに成功しました<br>";
    }
?>

<!DOCTYPE HTML>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>media</title>
</head>

<body>
    <a href="course.php?courseId=<?php echo $c_id;?>">コースに戻る→</a><br>
</body>
</html>
