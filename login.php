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
    <form id="loginForm" name="loginForm" action="" method="post">
        <br>
        <div align="center">
        <input type="text" name="name" placeholder="ユーザー名" style="text-align:center"><br>
        <input type="password" name="pass" placeholder="パスワード" style="text-align:center"><br>
        <input type="submit" name="login" value="ログイン">
        <br>
        </div>
    </form>
    <br>
    <div align="center">
        <hr>
        <p>新規登録はこちら</p>
        <form action="newuser.php">
            <input id="newuser" type="submit" value="新規登録">
        </form>
    </div>
    

<?php
    session_start();
    $error_message = "";

//DB接続
    $dsn = 'mysql:dbname=tb210133db;host=localhost';
    $user = 'tb-210133';
    $password = 'PHF7gy2jzx';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//ログイン機能
    if(isset($_POST["login"])){
        if(!empty($_POST["name"]) && !empty($_POST["pass"])){
            $name = $_POST["name"];
            $pass = $_POST["pass"];
            $check = 0;
        
        //ユーザー検索
            $sql = 'select * from UserDB';
            $stmt = $pdo -> query($sql);
            $result = $stmt -> fetchAll();
            foreach($result as $row){
                if($name == $row["name"] && $pass == $row["pass"]){
                //ログイン
                    $_SESSION["name"] = $name;
                    $login_url = "toppage.php";
                    header("Location:{$login_url}");
                    $check = 1;
                    exit;
                }
            }
            if($check == 0){
                $error_message = "ユーザー名またはパスワードが間違っています<br>";
            }
        }else{
            $error_message = "ユーザー名またはパスワードが空欄です<br>";
        }
        echo $error_message;
    }

    unset($pdo);
?>

  </body>
</html>